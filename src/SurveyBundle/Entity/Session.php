<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sessions")
 */
class Session
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Survey", inversedBy="sessions")
	 * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
	 */
	protected $survey;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotNull()
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotNull()
	 */
	protected $url;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $fromDate;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $toDate = null;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $active;

	/**
	 * @ORM\OneToMany(targetEntity="Answer", mappedBy="session", cascade={"persist"})
	 */
	protected $answers;

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
     * Set url
     *
     * @param string $url
     *
     * @return Session
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return Session
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return \DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set toDate
     *
     * @param \DateTime $toDate
     *
     * @return Session
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get toDate
     *
     * @return \DateTime
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Session
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set survey
     *
     * @param \SurveyBundle\Entity\Survey $survey
     *
     * @return Session
     */
    public function setSurvey(\SurveyBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;

        return $this;
    }

    /**
     * Get survey
     *
     * @return \SurveyBundle\Entity\Survey
     */
    public function getSurvey()
    {
        return $this->survey;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add answer
     *
     * @param \SurveyBundle\Entity\Answer $answer
     *
     * @return Session
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

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Session
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
