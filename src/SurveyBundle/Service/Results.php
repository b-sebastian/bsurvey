<?php

namespace SurveyBundle\Service;

use Ob\HighchartsBundle\Highcharts\Highchart;
use SurveyBundle\Entity\Question;
use SurveyBundle\Entity\Session;

class Results
{
	private $manager;

	public function __construct($manager)
	{
		$this->manager = $manager;
	}

	public function getSimplyCharts(Session $session, $questions)
	{
		$charts = array();

		foreach ($questions as $question) {
			$chartName = 'questionChart'.$question->getId();
			$answers = $this->manager->getRepository('SurveyBundle:Answer')->findBy(array(
				'session' => $session,
				'question' => $question
			));

			$series = array(
				array('type' => 'column', "name" => 'Votes', "data" => $this->getSerieData($question, $answers))
			);

			$ob = new Highchart();
			$ob->chart->renderTo($chartName);  // The #id of the div where to render the chart
			$ob->title->text($question->getTitle());
			$ob->xAxis->title(array('text'  => $question->getScale()->getLeftLabel().' - '.$question->getScale()->getRightLabel()));
			$ob->xAxis->categories(array_fill(0,$question->getScale()->getSize(),''));
			$ob->series($series);

			$charts[$chartName] = $ob;
		}

		return $charts;
	}

	private function getSerieData(Question $question, $answers)
	{
		$data = array_fill(0,$question->getScale()->getSize(),0);

		foreach ($answers as $answer) {
			$data[$answer->getAnswer()-1]++;
		}

		return $data;
	}
}