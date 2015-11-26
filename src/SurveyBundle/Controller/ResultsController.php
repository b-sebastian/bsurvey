<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ResultsController extends Controller
{
	/**
	 * @Route("/results", name="results")
	 */
	public function showResults(Request $request)
	{
		$manager = $this->getDoctrine()->getManager();

		$sr = $manager->getRepository('SurveyBundle:Session');
		$list = $sr->createQueryBuilder('se')
			->leftJoin('se.survey', 'su')
			->where('su.user = :user')
			->setParameter('user', $this->getUser())->getQuery()->getResult();

		return $this->render('SurveyBundle:results:index.html.twig', array(
			'sessions' => $list
		));
	}
}
