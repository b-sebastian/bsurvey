<?php

namespace SurveyBundle\Form\Type;

use SurveyBundle\Entity\Answer;
use SurveyBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
		{
			$form = $event->getForm();
			$data = $event->getData();

			/* Check we're looking at the right data/form */
			if ($data instanceof Answer)
			{
				$type = $data->getQuestion()->getScale()->getType();
				if ($type == 'radio') {
					$form->add('answer', 'choice', array(
						'choices' => $this->getChoices($data->getQuestion()),
						'expanded' => true,
						'label' => $data->getQuestion()->getTitle()
					));
				} else if ($type == 'text') {
					$form->add('answer', 'textarea', array(
						'attr' => array('rows' => $data->getQuestion()->getScale()->getSize()),
						'label' => $data->getQuestion()->getTitle()
					));
				}
			}
		});
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'SurveyBundle\Entity\Answer',
		));
	}

	public function getName()
	{
		return 'sb_answer';
	}

	private function getChoices(Question $question)
	{
		$choices = array();
		for ($i = 1; $i <= $question->getScale()->getSize(); $i++) {
			$choices[$i] = $i;
		}

		return $choices;
	}
}