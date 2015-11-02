<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use SurveyBundle\Entity\Scale;
use SurveyBundle\Entity\SurveyType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151021174503 extends AbstractMigration implements ContainerAwareInterface
{
	private $container;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
		$scales = $this->getScalesData();
		$surveyTypes = $this->getSurveyTypes();
		$em = $this->container->get('doctrine.orm.entity_manager');

		foreach($surveyTypes as $surveyType) {
			$typeEntity = new SurveyType();
			$typeEntity->setName($surveyType['name']);
			$typeEntity->setDescription($surveyType['description']);

			$em->persist($typeEntity);
			$em->flush();
		}

		foreach($scales as $scale) {
			$scaleEntity = new Scale();
			$scaleEntity->setTitle($scale['title']);
			$scaleEntity->setSize($scale['size']);
			$scaleEntity->setType($scale['type']);
			$scaleEntity->setLeftLabel($scale['leftLabel']);
			$scaleEntity->setRightLabel($scale['rightLabel']);

			$em->persist($scaleEntity);
			$em->flush();
		}
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

	private function getSurveyTypes()
	{
		return array(
			array(
				'name' => 'Link',
				'description' => 'Access to this survey will have only peoples with link to it.'),
			array(
				'name' => 'Friends',
				'description' => 'Access to this survey will have only users selected from your friend list.')
		);
	}

	private function getScalesData()
	{
		return array(
			array(
				'type' => 'radio',
				'size' => '2',
				'title' => 'Yes/No',
				'leftLabel' => 'No',
				'rightLabel' => 'Yes'
			),
			array(
				'type' => 'radio',
				'size' => '3',
				'title' => '3 Steps Scale',
				'leftLabel' => 'No',
				'rightLabel' => 'Yes'
			),
			array(
				'type' => 'radio',
				'size' => '4',
				'title' => '4 Steps Scale',
				'leftLabel' => 'No',
				'rightLabel' => 'Yes'
			),
			array(
				'type' => 'radio',
				'size' => '5',
				'title' => '5 Steps Scale',
				'leftLabel' => 'Definitely No',
				'rightLabel' => 'Definitely Yes'
			),
			array(
				'type' => 'radio',
				'size' => '6',
				'title' => '6 Steps Scale',
				'leftLabel' => 'Definitely No',
				'rightLabel' => 'Definitely Yes'
			),
			array(
				'type' => 'text',
				'size' => '4',
				'title' => 'Text Answer',
				'leftLabel' => '',
				'rightLabel' => ''
			),
			array(
				'type' => 'radio',
				'size' => '2',
				'title' => 'Female/Male',
				'leftLabel' => 'Female',
				'rightLabel' => 'Male'
			),
			array(
				'type' => 'checkbox',
				'size' => '3',
				'title' => 'CheckBoxes',
				'leftLabel' => '',
				'rightLabel' => ''
			)
		);
	}
}
