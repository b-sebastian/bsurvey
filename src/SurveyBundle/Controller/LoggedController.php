<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoggedController extends Controller
{
	/**
	 * @Route("/overview", name="overview")
	 */
	public function overviewAction(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$surveys = $manager->getRepository('SurveyBundle:Survey')->findBy(array(
			'user' => $this->getUser(),
			'deleted' => false
		));

		$sessions = $manager->getRepository('SurveyBundle:Session')->createQueryBuilder('se')
			->leftJoin('se.survey', 'su')
			->where('su.user = :user')
			->setParameter('user', $this->getUser())
			->orderBy('se.fromDate', 'DESC')
			//->setMaxResults(10)
			->getQuery()->getResult();

		$last_answer = $manager->getRepository('SurveyBundle:Answer')->createQueryBuilder('a')
			->leftJoin('a.question', 'q')
			->where('q.user = :user')
			->setParameter('user', $this->getUser())
			->setMaxResults(1)
			->orderBy('a.id', 'DESC')
			->getQuery()->getResult();

		$thumbs = array(
			'last_answer' => $last_answer[0],
			'survey_count' => count($surveys),
			'session_count' => count($sessions)
		);

		$tables = array(
			'surveys' => $surveys,
			'sessions' => $sessions
		);

		return $this->render('SurveyBundle::overview.html.twig', array('thumbs' => $thumbs, 'tables' => $tables));
	}
}
