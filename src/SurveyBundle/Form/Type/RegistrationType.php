<?php

namespace SurveyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('forename', 'text', array('required' => false));
		$builder->add('surname', 'text', array('required' => false));
		$builder->add(
			'terms',
			'checkbox',
			array('property_path' => 'termsAccepted',
				'label' => 'I have read and accepted the terms of use')
		);
		$builder->add('Register', 'submit', array('attr' => array('class' => 'btn-primary')));
	}

	public function getName()
	{
		return 'registration';
	}

	public function getParent()
	{
		return 'fos_user_registration';
	}
}