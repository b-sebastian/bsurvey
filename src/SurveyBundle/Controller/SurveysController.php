<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SurveyBundle\Entity\Survey;
use SurveyBundle\Entity\SurveyUrl;
use SurveyBundle\Form\Type\SurveyType;
use SurveyBundle\Service\RandomString;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class SurveysController extends Controller
{
	/**
	 * @Route("/surveys/add", name="survey_add")
	 */
	public function addForm(Request $request)
	{
		$survey = new Survey();

		$form = $this->createForm(new SurveyType($this->getUser()), $survey);
		$form->handleRequest($request);

		if($form->isValid()) {
			$survey->setUser($this->getUser());
			$survey->setDate(new \DateTime());

			$em = $this->getDoctrine()->getManager();
			$em->persist($survey);
			$em->flush();

			return $this->redirect($this->generateUrl('survey_add', array(
				'ss' => 1
			)));
		}

		return $this->render('SurveyBundle:survey:add.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/surveys", name="surveys")
	 */
	public function showSurveys(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$surveys = $manager->getRepository('SurveyBundle:Survey');
		$list = $surveys->findBy(array(
			'user' => $this->getUser(),
			'deleted' => false
		));

		return $this->render('SurveyBundle:survey:index.html.twig', array(
			'surveys' => $list
		));
	}

	/**
	 * @Route("/survey/{id}", name="survey_edit", requirements={"id": "\d+"})
	 */
	public function editForm(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();

		$survey = $manager->getRepository('SurveyBundle:Survey')->find($id);

		if ($survey->getUser() != $this->getUser())
			throw new AccessDeniedException();

		$form = $this->createForm(new SurveyType($this->getUser()), $survey);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if($form->isValid()) {
				$survey->setDate(new \DateTime());
				$manager->persist($survey);
				$manager->flush();

				return $this->redirect($this->generateUrl('surveys', array(
					'se' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:survey:edit.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/survey/delete/{id}", name="survey_delete", requirements={"id": "\d+"})
	 */
	public function deleteSurvey(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();
		$survey = $manager->getRepository('SurveyBundle:Survey')->find($id);

		if ($survey->getUser() != $this->getUser())
			throw new AccessDeniedException();

		$survey->setDeleted(true);

		$manager->persist($survey);
		$manager->flush();

		return $this->redirect($this->generateUrl('surveys', array(
			'sd' => 1
		)));
	}

	/**
	 * @Route("/ajax/survey/url/check", name="ajax_survey_url_check", condition="request.isXmlHttpRequest()")
	 */
	public function ajaxSurveyUrlCheck(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();
		$id = $request->request->get('id');

		$surveyUrl = $manager->getRepository('SurveyBundle:Survey')->find($id)->getSurveyUrls()->last();

		if( !is_a($surveyUrl, 'SurveyUrl') || !$surveyUrl->getActive())
		{
			$data = array('url' => "");
			return new Response(json_encode($data),200);
		}

		$data = array('url' => $newUrlForSurvey = $request->getScheme().'://'.$request->getHttpHost().$this->generateUrl('survey_show', array('url' => $surveyUrl->getUrl())));

		return new Response(json_encode($data),200);
	}

	/**
	 * @Route("/ajax/survey/url", name="ajax_survey_url", condition="request.isXmlHttpRequest()")
	 */
	public function ajaxSurveyUrl(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();
		$id = $request->request->get('id');
		$survey = $manager->getRepository('SurveyBundle:Survey')->find($id);
		$randomString = RandomString::generate(20);
		$newUrlForSurvey = $request->getScheme().'://'.$request->getHttpHost().$this->generateUrl('survey_show', array('url' => $randomString));

		$url = new SurveyUrl();
		$url->setSurvey($survey);
		$url->setFromDate(new \DateTime());
		$url->setActive(true);
		$url->setUrl($randomString);

		$manager->persist($url);
		$manager->flush();

		$data = array(
			'url' => $newUrlForSurvey
		);

		return new Response(json_encode($data),200);
	}

	/**
	 * @Route("/ajax/survey/url/off", name="ajax_survey_url_off", condition="request.isXmlHttpRequest()")
	 */
	public function ajaxSurveyUrlOff(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();
		$id = $request->request->get('id');

		$surveyUrl = $manager->getRepository('SurveyBundle:Survey')->find($id)->getSurveyUrls()->last();
		$surveyUrl->setActive(false);
		$surveyUrl->setToDate(new \DateTime());

		$manager->persist($surveyUrl);
		$manager->flush();

		$data = array(
			'deleted' => 1
		);

		return new Response(json_encode($data),200);
	}
}
