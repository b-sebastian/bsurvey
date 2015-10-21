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
		return $this->render('SurveyBundle::overview.html.twig');
	}
}
