<?php

namespace SurveyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SurveyBundle\Form\Type\UserType;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
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
			'last_answer' => isset($last_answer[0]) ? $last_answer[0] : null,
			'survey_count' => count($surveys),
			'session_count' => count($sessions)
		);

		$tables = array(
			'surveys' => $surveys,
			'sessions' => $sessions
		);

		return $this->render('SurveyBundle::overview.html.twig', array('thumbs' => $thumbs, 'tables' => $tables));
	}

	/**
	 * @Route("/profile/edit", name="profile_edit")
	 */
	public function profileEditAction(Request $request)
	{
		$user = $this->getUser();
		$form = $this->createForm(new UserType(), $user);

		if ($request->isMethod('POST')) {
			$old_pass = $request->request->get('sb_profile_edit')['old_password'];
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($user);
			$old_pass = $encoder->encodePassword($old_pass, $user->getSalt());

			if ($old_pass != $user->getPassword()) {
				return $this->redirect($this->generateUrl('profile_edit', array(
					'se' => 1
				)));
			}

			$form->handleRequest($request);
			if($form->isValid()) {
				$userManager = $this->get('fos_user.user_manager');
				$user->setPlainPassword($request->request->get('sb_profile_edit')['password']);
				$userManager->updateUser($user);

				return $this->redirect($this->generateUrl('profile_edit', array(
					'ss' => 1
				)));
			}
		}

		return $this->render('SurveyBundle:profile:edit.html.twig', array(
			'form' => $form->createView()
		));
	}
}
