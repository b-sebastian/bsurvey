<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="answers")
 */
class Answer
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Session", inversedBy="answers")
	 * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
	 */
	protected $session;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="answers")
	 * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
	 */
	protected $user = null;

	/**
	 * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 */
	protected $question;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $ip;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $userAgent;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $date = null;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $answer;

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
     * Set ip
     *
     * @param string $ip
     *
     * @return Answer
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return Answer
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Answer
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
     * Set answer
     *
     * @param string $answer
     *
     * @return Answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set session
     *
     * @param \SurveyBundle\Entity\Session $session
     *
     * @return Answer
     */
    public function setSession(\SurveyBundle\Entity\Session $session = null)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \SurveyBundle\Entity\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set user
     *
     * @param \SurveyBundle\Entity\User $user
     *
     * @return Answer
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
     * Set question
     *
     * @param \SurveyBundle\Entity\Question $question
     *
     * @return Answer
     */
    public function setQuestion(\SurveyBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \SurveyBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
