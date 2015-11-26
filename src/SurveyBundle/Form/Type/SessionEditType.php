<?php

namespace SurveyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionEditType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array(
			'required' => true
		));

		$builder->add('Save', 'submit', array('attr' => array('class' => 'btn-primary')));
	}

	public function getName()
	{
		return 'sb_session_edit';
	}
}