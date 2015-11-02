<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SurveyBundle\Entity\Question;
use SurveyBundle\Form\Type\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuestionsController extends Controller
{
	/**
	 * @Route("/questions/add", name="question_add")
	 */
	public function addForm(Request $request)
	{
		$question = new Question();

		$form = $this->createForm(new QuestionType(), $question);
		$form->handleRequest($request);

		if($form->isValid()) {
			$question->setUser($this->getUser());
			$question->setDate(new \DateTime());

			$em = $this->getDoctrine()->getManager();
			$em->persist($question);
			$em->flush();

			return $this->redirect($this->generateUrl('question_add', array(
				'ss' => 1
			)));
		}

		return $this->render('SurveyBundle:question:add.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/questions", name="questions")
	 */
	public function showQuestions(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$questions = $manager->getRepository('SurveyBundle:Question');
		$list = $questions->findBy(array(
			'user' => $this->getUser(),
			'deleted' => false
			));

		return $this->render('SurveyBundle:question:index.html.twig', array(
			'questions' => $list
		));
	}

	/**
	 * @Route("/questions/{id}", name="questions_edit", requirements={"id": "\d+"})
	 */
	public function editForm(Request $request, $id)
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

				return $this->redirect($this->generateUrl('questions', array(
					'se' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:question:edit.html.twig', array(
			'form' => $form->createView()
		));
	}

	/**
	 * @Route("/questions/delete/{id}", name="questions_delete", requirements={"id": "\d+"})
	 */
	public function deleteQuestion(Request $request, $id)
	{
		$sd = 0;
		$manager = $this->getDoctrine()->getManager();
		$question = $manager->getRepository('SurveyBundle:Question')->find($id);

		if ($question->getUser() == $this->getUser()) {
			$question->setDeleted(true);

			$manager->persist($question);
			$manager->flush();
			$sd = 1;
		}

		return $this->redirect($this->generateUrl('questions', array(
			'sd' => $sd
		)));
	}
}
