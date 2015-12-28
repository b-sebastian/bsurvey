<?php

namespace SurveyBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SurveyBundle\Entity\Session;
use SurveyBundle\Form\Type\SessionEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SurveyBundle\Entity\Answer;
use SurveyBundle\Form\Type\SessionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use SurveyBundle\Service\Results;

class SessionsController extends Controller
{
	/**
	 * @Route("/session/show/{url}", name="survey_show", requirements={"url": "[0-9a-zA-Z]+"})
	 */
	public function indexAction(Request $request, $url)
	{
		$manager = $this->getDoctrine()->getManager();
		$session = $manager->getRepository('SurveyBundle:Session')->findOneByUrl($url);

		if (!is_object($session))
			return $this->render('SurveyBundle:display:index.html.twig', array('not_found' => 'There is no survey here. Maybe you did a typo?'));

		if (!$session->getActive())
			return $this->render('SurveyBundle:display:index.html.twig', array('closed' => 'Survey has been closed'));

		$survey = $session->getSurvey();

		if ($survey->getSurveyType()->getName() == 'Friends')
			return $this->render('SurveyBundle:display:index.html.twig', array('notAvailable' => 'This survey type is not available now'));

		if ($request->get('se') != 1 && $request->get('ss') != 1) {
			if ($this->getUser()) {
				$exist = $manager->getRepository('SurveyBundle:Answer')->findOneBy(array('session' => $session, 'user' => $this->getUser()));
			} else {
				$exist = $manager->getRepository('SurveyBundle:Answer')->findOneBy(array('session' => $session, 'ip' => $request->server->get('REMOTE_ADDR')));
			}

			if ($exist !== null) {
				return $this->redirect($this->generateUrl('survey_show', array(
					'url' => $session->getUrl(),
					'se' => 1
				)));
			}
		}

		$questions = $session->getQuestions();
		$session->getAnswers()->clear();

		foreach ($questions as $question)
		{
			$answer = new Answer();
			$answer->setDate(new \DateTime());
			$answer->setUserAgent($request->server->get('HTTP_USER_AGENT'));
			$answer->setQuestion($question);
			$answer->setSession($session);
			$answer->setIp($request->server->get('REMOTE_ADDR'));

			if ($this->getUser())
				$answer->setUser($this->getUser());

			$session->addAnswer($answer);
		}

		$form = $this->createForm(new SessionType(), $session);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);

			if ($form->isValid()) {
				foreach ($session->getAnswers() as $a) {
					$manager->persist($a);
					$manager->flush();
				}

				return $this->redirect($this->generateUrl('survey_show', array(
					'url' => $session->getUrl(),
					'ss' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:display:index.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/session/result/{id}", name="session_result", requirements={"id": "\d+"})
	 */
	public function resultAction(Request $request, Session $session)
	{
		$manager = $this->getDoctrine()->getManager();
		$questions = $session->getQuestions();
		$radios = array();
		$texts = array();

		foreach ($questions as $question) {
			if ($question->getScale()->getType() == 'radio')
				$radios[] = $question;
			else if ($question->getScale()->getType() == 'text')
				$texts['questionTable'.$question->getId()] = $question;
		}

		$resultService = new Results($manager);
		$charts = $resultService->getSimplyCharts($session, $radios);

		return $this->render('SurveyBundle:session:result.html.twig', array(
			'charts' => $charts,
			'session' => $session,
			'texts' => $texts
		));
	}

	/**
	 * @Route("/session/{id}", name="session_edit", requirements={"id": "\d+"})
	 */
	public function editForm(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();

		$session  = $manager->getRepository('SurveyBundle:Session')->find($id);

		if ($session->getSurvey()->getUser() != $this->getUser())
			throw new AccessDeniedException;

		$form = $this->createForm(new SessionEditType(), $session);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if($form->isValid()) {
				$manager->persist($session);
				$manager->flush();

				return $this->redirect($this->generateUrl('results', array(
					'se' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:session:edit.html.twig', array(
			'form' => $form->createView()
		));
	}
}
