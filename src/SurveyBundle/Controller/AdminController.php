<?php

namespace SurveyBundle\Controller;

use SurveyBundle\Form\Type\SessionEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use SurveyBundle\Form\Type\SurveyType;
use SurveyBundle\Form\Type\QuestionType;
use SurveyBundle\Service\Results;
use SurveyBundle\Entity\Session;

class AdminController extends Controller
{
	/**
	 * @Route("/admin/dashboard", name="admin_dashboard")
	 */
	public function indexAction(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$surveys = $manager->getRepository('SurveyBundle:Survey')->findBy(array(), array('id' => 'DESC'));
		$sessions = $manager->getRepository('SurveyBundle:Session')->findBy(array(), array('id' => 'DESC'));
		$last_answer = $manager->getRepository('SurveyBundle:Answer')->findOneBy(array(),array('id' => 'DESC'));

		$thumbs = array(
			'last_answer' => $last_answer,
			'survey_count' => count($surveys),
			'session_count' => count($sessions)
		);

		$tables = array(
			'surveys' => $surveys,
			'sessions' => $sessions
		);

		return $this->render('SurveyBundle:admin:index.html.twig', array('thumbs' => $thumbs, 'tables' => $tables));
	}

	/**
	 * @Route("/admin/surveys", name="admin_surveys")
	 */
	public function showSurveys(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$surveys = $manager->getRepository('SurveyBundle:Survey');
		$list = $surveys->findBy(array(
			'deleted' => false
		));

		return $this->render('SurveyBundle:admin:surveys.html.twig', array(
			'surveys' => $list
		));
	}

	/**
	 * @Route("/admin/survey/{id}", name="admin_survey_edit", requirements={"id": "\d+"})
	 */
	public function editForm(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();
		$survey = $manager->getRepository('SurveyBundle:Survey')->find($id);

		$form = $this->createForm(new SurveyType($survey->getUser()), $survey);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if($form->isValid()) {
				$survey->setDate(new \DateTime());
				$manager->persist($survey);
				$manager->flush();

				return $this->redirect($this->generateUrl('admin_surveys', array(
					'se' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:admin/survey:edit.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/admin/survey/delete/{id}", name="admin_survey_delete", requirements={"id": "\d+"})
	 */
	public function deleteSurvey(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();
		$survey = $manager->getRepository('SurveyBundle:Survey')->find($id);

		$survey->setDeleted(true);
		$manager->persist($survey);
		$manager->flush();

		return $this->redirect($this->generateUrl('admin_surveys', array(
			'sd' => 1
		)));
	}

	/**
	 * @Route("/admin/questions", name="admin_questions")
	 */
	public function showQuestions(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$questions = $manager->getRepository('SurveyBundle:Question');
		$list = $questions->findBy(array(
			'deleted' => false
		));

		return $this->render('SurveyBundle:admin/question:index.html.twig', array(
			'questions' => $list
		));
	}

	/**
	 * @Route("/admin/questions/{id}", name="admin_questions_edit", requirements={"id": "\d+"})
	 */
	public function editsForm(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();
		$question = $manager->getRepository('SurveyBundle:Question')->find($id);

		$form = $this->createForm(new QuestionType(), $question);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if($form->isValid()) {
				$question->setDate(new \DateTime());
				$manager->persist($question);
				$manager->flush();

				return $this->redirect($this->generateUrl('admin_questions', array(
					'se' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:admin/question:edit.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/admin/questions/delete/{id}", name="admin_questions_delete", requirements={"id": "\d+"})
	 */
	public function deleteQuestion(Request $request, $id)
	{
		$manager = $this->getDoctrine()->getManager();
		$question = $manager->getRepository('SurveyBundle:Question')->find($id);

		$question->setDeleted(true);

		$manager->persist($question);
		$manager->flush();

		return $this->redirect($this->generateUrl('admin_questions', array(
			'sd' => 1
		)));
	}

	/**
	 * @Route("/admin/sessions", name="admin_sessions")
	 */
	public function showResults(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$sr = $manager->getRepository('SurveyBundle:Session');
		$list = $sr->createQueryBuilder('se')
			->leftJoin('se.survey', 'su')->getQuery()->getResult();

		return $this->render('SurveyBundle:admin/session:index.html.twig', array(
			'sessions' => $list
		));
	}

	/**
	 * @Route("/admin/session/result/{id}", name="admin_session_result", requirements={"id": "\d+"})
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

		return $this->render('SurveyBundle:admin/session:result.html.twig', array(
			'charts' => $charts,
			'session' => $session,
			'texts' => $texts
		));
	}

	/**
	 * @Route("/admin/session/{id}", name="admin_session_edit", requirements={"id": "\d+"})
	 */
	public function editSessionForm(Request $request, Session $session)
	{
		$manager = $this->getDoctrine()->getManager();
		$form = $this->createForm(new SessionEditType(), $session);

		if ($request->isMethod('POST')) {
			$form->handleRequest($request);
			if($form->isValid()) {
				$manager->persist($session);
				$manager->flush();

				return $this->redirect($this->generateUrl('admin_session_result', array(
					'se' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:admin/session:edit.html.twig', array(
			'form' => $form->createView()
		));
	}
}
