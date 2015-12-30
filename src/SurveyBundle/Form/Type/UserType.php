<?php

namespace SurveyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('forename', 'text', array('required' => false));
		$builder->add('surname', 'text', array('required' => false));
		$builder->add('email', 'email', array());
		$builder->add('old_password', 'password', array('mapped' => false));
		$builder->add('password', 'password', array('label' => 'New Password'));
		$builder->add('terms_accepted', 'hidden', array('data' => true));
		$builder->add('Save', 'submit', array('attr' => array('class' => 'btn-primary')));
	}

	public function getName()
	{
		return 'sb_profile_edit';
	}
}