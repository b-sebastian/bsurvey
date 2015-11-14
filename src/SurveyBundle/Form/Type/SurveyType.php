<?php

namespace SurveyBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use SurveyBundle\Entity\QuestionRepository;
use SurveyBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SurveyType extends AbstractType
{
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text', array());
		$builder->add('description', 'textarea', array('attr' => array('rows' => 5)));
		$builder->add('surveyType', 'entity', array(
			'class' => 'SurveyBundle\Entity\SurveyType',
			'choice_label' => 'name'
		));

		$builder->add('questions', 'entity', array(
			'class' => 'SurveyBundle\Entity\Question',
			'query_builder' => function(QuestionRepository $er ) {
				return $er->createQueryBuilder('q')
					->where('q.user = :user')
					->andWhere('q.deleted = :deleted')
					->setParameter('deleted', false)
					->setParameter('user', $this->user);
			},
			'choice_label' => 'title',
			'multiple' => true,
			'expanded' => true,
			'property' => 'line'
		));
		$builder->add('Add', 'submit', array('attr' => array('class' => 'btn-primary')));
	}

	public function getName()
	{
		return 'sb_survey';
	}


}