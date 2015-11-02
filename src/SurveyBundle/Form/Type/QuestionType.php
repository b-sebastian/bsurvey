<?php

namespace SurveyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text', array());
		$builder->add('description', 'textarea', array('attr' => array('rows' => 5)));
		$builder->add('scale', 'entity', array(
			'class' => 'SurveyBundle\Entity\Scale',
			'choice_label' => 'title'
		));
		$builder->add('Add', 'submit', array('attr' => array('class' => 'btn-primary')));
	}

	public function getName()
	{
		return 'sb_question';
	}
}