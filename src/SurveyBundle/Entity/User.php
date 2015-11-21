<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	protected $email;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $forename;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $surname;

	/**
	 * @Assert\NotBlank()
	 * @Assert\True()
	 */
	protected $termsAccepted;

	/**
	 * @ORM\OneToMany(targetEntity="Question", mappedBy="user")
	 */
	protected $questions;

	/**
	 * @ORM\OneToMany(targetEntity="Survey", mappedBy="user")
	 */
	protected $surveys;

	/**
	 * @ORM\OneToMany(targetEntity="Answer", mappedBy="user")
	 */
	protected $answers;

	public function __construct()
	{
		parent::__construct();
		$this->questions = new ArrayCollection();
		$this->surveys = new ArrayCollection();
	}

    /**
     * Add question
     *
     * @param \SurveyBundle\Entity\Question $question
     *
     * @return User
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
     * Set forename
     *
     * @param string $forename
     *
     * @return User
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Get forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

	public function getTermsAccepted()
	{
		return $this->termsAccepted;
	}

	public function setTermsAccepted($termsAccepted)
	{
		$this->termsAccepted = (bool) $termsAccepted;
	}

    /**
     * Add survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return User
     */
    public function addSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->surveys[] = $survey;

        return $this;
    }

    /**
     * Remove survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     */
    public function removeSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->surveys->removeElement($survey);
    }

    /**
     * Get surveys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSurveys()
    {
        return $this->surveys;
    }

    /**
     * Add answer
     *
     * @param \SurveyBundle\Entity\Answer $answer
     *
     * @return User
     */
    public function addAnswer(\SurveyBundle\Entity\Answer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \SurveyBundle\Entity\Answer $answer
     */
    public function removeAnswer(\SurveyBundle\Entity\Answer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }
}
