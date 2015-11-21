<?php

namespace SurveyBundle\Service;

use SurveyBundle\Entity\Question;
use Symfony\Component\Form\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class QuestionChoiceList extends LazyChoiceList
{
	private $question;

	public function __construct(ChoiceLoaderInterface $loaderInterface, Question $question)
	{
		parent::__construct($loaderInterface);

		$this->question = $question;
	}

	protected function loadChoiceList()
	{
		$choices = array(1 => $this->question->getScale()->getLeftLabel());

		for ($i = 2; $i < $this->question->getScale()->getSize(); $i++) {
			$choices[$i] = $i;
		}

		$choices[] = $this->question->getScale()->getRightLabel();

		$choices = new SimpleChoiceList($choices);

		return $choices;
	}
}