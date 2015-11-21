<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="SurveyBundle\Entity\QuestionRepository")
 * @ORM\Table(name="questions")
 */
class Question
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="questions")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	protected $user;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $description;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $title;

	/**
	 * @ORM\ManyToOne(targetEntity="Scale", inversedBy="questions")
	 * @ORM\JoinColumn(name="scale_id", referencedColumnName="id")
	 */
	protected $scale;

	/**
	 * @ORM\ManyToMany(targetEntity="Survey", mappedBy="questions")
	 */
	protected $surveys;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $date;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $deleted = false;

	/**
	 * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
	 */
	protected $answers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->surveys = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Question
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
     * @return Question
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
     * @return Question
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
     * @return Question
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

		foreach ($this->surveys as $survey)
		{
			$survey->removeQuestion($this);
		}

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
     * @return Question
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
     * Set scale
     *
     * @param \SurveyBundle\Entity\Scale $scale
     *
     * @return Question
     */
    public function setScale(\SurveyBundle\Entity\Scale $scale = null)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get scale
     *
     * @return \SurveyBundle\Entity\Scale
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Add survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return Question
     */
    public function addSurvey(\SurveyBundle\Entity\Survey $survey)
    {
        $this->surveys[] = $survey;
		$survey->addQuestion($this);

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
		$survey->removeQuestion($this);
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
     * @return Question
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
