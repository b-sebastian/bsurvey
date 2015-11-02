<?php

namespace SurveyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="scales")
 */
class Scale
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=25)
	 * @Assert\NotNull()
	 */
	protected $type;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\NotNull()
	 */
	protected $size;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotNull()
	 */
	protected $title;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $leftLabel;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $rightLabel;

	/**
	 * @ORM\OneToMany(targetEntity="Question", mappedBy="scale")
	 */
	protected $questions;

	public function __construct()
	{
		$this->questions = new ArrayCollection();
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
     * Set type
     *
     * @param string $type
     *
     * @return Scale
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Scale
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Scale
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
     * Set leftLabel
     *
     * @param string $leftLabel
     *
     * @return Scale
     */
    public function setLeftLabel($leftLabel)
    {
        $this->leftLabel = $leftLabel;

        return $this;
    }

    /**
     * Get leftLabel
     *
     * @return string
     */
    public function getLeftLabel()
    {
        return $this->leftLabel;
    }

    /**
     * Set rightLabel
     *
     * @param string $rightLabel
     *
     * @return Scale
     */
    public function setRightLabel($rightLabel)
    {
        $this->rightLabel = $rightLabel;

        return $this;
    }

    /**
     * Get rightLabel
     *
     * @return string
     */
    public function getRightLabel()
    {
        return $this->rightLabel;
    }

    /**
     * Add question
     *
     * @param \SurveyBundle\Entity\Question $question
     *
     * @return Scale
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
}
