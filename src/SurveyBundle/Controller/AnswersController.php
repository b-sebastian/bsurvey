<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
	public function addForm(Request $request, $url)
	{

		return $this->render($url);
	}
}
