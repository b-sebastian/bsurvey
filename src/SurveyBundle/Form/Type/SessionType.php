<?php

namespace SurveyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('answers', 'collection', array(
			'type' => new AnswerType(),
			'label' => 'Questions'));

		$builder->add('Send', 'submit', array('attr' => array('class' => 'btn-primary')));
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'SurveyBundle\Entity\Session',
		));
	}

	public function getName()
	{
		return 'sb_session';
	}
}