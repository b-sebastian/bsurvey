<?php

namespace SurveyBundle\Entity;

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

	public function getForename()
	{
		return $this->forename;
	}

	public function setForename($forename)
	{
		$this->forename = $forename;
	}

	public function getSurname()
	{
		return $this->surname;
	}

	public function setSurname($surname)
	{
		$this->surname = $surname;
	}

	public function getTermsAccepted()
	{
		return $this->termsAccepted;
	}

	public function setTermsAccepted($termsAccepted)
	{
		$this->termsAccepted = (bool) $termsAccepted;
	}
}