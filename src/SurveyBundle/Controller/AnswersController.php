<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SurveyBundle\Entity\Answer;
use SurveyBundle\Entity\AnswerRepository;
use SurveyBundle\Form\Type\SessionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class AnswersController extends Controller
{
	/**
	 * @Route("/survey/show/{url}", name="survey_show", requirements={"url": "[0-9a-zA-Z]+"})
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
				$exist = $manager->getRepository('SurveyBundle:Answer')->findOneByUser($this->getUser());
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

		$questions = $survey->getQuestions();
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
}
