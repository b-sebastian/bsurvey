<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="surveys_urls")
 */
class SurveyUrl
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Survey", inversedBy="surveyUrls")
	 * @ORM\JoinColumn(name="survey_id", referencedColumnName="id")
	 */
	protected $survey;

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
     * @return SurveyUrl
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
     * Set active
     *
     * @param boolean $active
     *
     * @return SurveyUrl
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
     * @return SurveyUrl
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
     * Set fromDate
     *
     * @param \DateTime $fromDate
     *
     * @return SurveyUrl
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
     * @return SurveyUrl
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
}
