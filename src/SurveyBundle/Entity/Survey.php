<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="surveys")
 */
class Survey
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="surveys")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	protected $user;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $description;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $title;

	/**
	 * @ORM\OneToMany(targetEntity="Question", mappedBy="survey")
	 */
	protected $questions;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $date;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $deleted = false;

	/**
	 * @ORM\ManyToOne(targetEntity="SurveyType", inversedBy="surveys")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	protected $surveyType;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Survey
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Survey
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Survey
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Survey
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set user
     *
     * @param \SurveyBundle\Entity\User $user
     *
     * @return Survey
     */
    public function setUser(\SurveyBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SurveyBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add question
     *
     * @param \SurveyBundle\Entity\Question $question
     *
     * @return Survey
     */
    public function addQuestion(\SurveyBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \SurveyBundle\Entity\Question $question
     */
    public function removeQuestion(\SurveyBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set surveyType
     *
     * @param \SurveyBundle\Entity\SurveyType $surveyType
     *
     * @return Survey
     */
    public function setSurveyType(\SurveyBundle\Entity\SurveyType $surveyType = null)
    {
        $this->surveyType = $surveyType;

        return $this;
    }

    /**
     * Get surveyType
     *
     * @return \SurveyBundle\Entity\SurveyType
     */
    public function getSurveyType()
    {
        return $this->surveyType;
    }
}
