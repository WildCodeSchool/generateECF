<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student
{
    const MALE = 1;
    const FEMALE = 2;
    const GENDER_UNDEFINED = 0;

    public function __construct()
    {
        $this->setValidateEvalSuppTwo(false);
        $this->setValidateEvalSuppOne(false);
        $this->setValidateActivityTwo(false);
        $this->setValidateActivityOne(false);
        $this->setObservationStudent("a remplir");
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=255)
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfBirth", type="datetime")
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Promo", inversedBy="students")
     */
    private $promo;

    /**
     * @var bool
     *
     * @ORM\Column(name="validateActivityOne", type="boolean")
     */
    private $validateActivityOne;

    /**
     * @var string
     *
     * @ORM\Column(name="commActivityOne", type="text", nullable=true)
     *
     * @Assert\Length(
     *      max = 815,
     *      maxMessage = "Your commActivityOne cannot be longer than {{ limit }} characters"
     * )
     */
    private $commActivityOne;

    /**
     * @var bool
     *
     * @ORM\Column(name="validateEvalSuppOne", type="boolean")
     */
    private $validateEvalSuppOne;

    /**
     * @var bool
     *
     * @ORM\Column(name="validateActivityTwo", type="boolean")
     */
    private $validateActivityTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="commActivityTwo", type="text", nullable=true)
     *
     * @Assert\Length(
     *      max = 815,
     *      maxMessage = "Your commActivityTwo cannot be longer than {{ limit }} characters"
     * )
     */
    private $commActivityTwo;

    /**
     * @var bool
     *
     * @ORM\Column(name="validateEvalSuppTwo", type="boolean")
     */
    private $validateEvalSuppTwo;

    /**
     * @var string
     *
     * @ORM\Column(name="observationStudent", type="text")
     *
     * @Assert\Length(
     *      max = 815,
     *      maxMessage = "Your observationStudent cannot be longer than {{ limit }} characters"
     * )
     */
    private $observationStudent;

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * @param \DateTime $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @param string $promo
     */
    public function setPromo($promo) {
        $this->promo = $promo;
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set validateActivityOne
     *
     * @param boolean $validateActivityOne
     *
     * @return Student
     */
    public function setValidateActivityOne($validateActivityOne)
    {
        $this->validateActivityOne = $validateActivityOne;

        return $this;
    }

    /**
     * Get validateActivityOne
     *
     * @return bool
     */
    public function getValidateActivityOne()
    {
        return $this->validateActivityOne;
    }

    /**
     * Set commActivityOne
     *
     * @param string $commActivityOne
     *
     * @return Student
     */
    public function setCommActivityOne($commActivityOne)
    {
        $this->commActivityOne = trim(preg_replace('/\s+/', ' ', $commActivityOne));

        return $this;
    }

    /**
     * Get commActivityOne
     *
     * @return string
     */
    public function getCommActivityOne()
    {
        return $this->commActivityOne;
    }

    /**
     * Set validateEvalSuppOne
     *
     * @param boolean $validateEvalSuppOne
     *
     * @return Student
     */
    public function setValidateEvalSuppOne($validateEvalSuppOne)
    {
        $this->validateEvalSuppOne = $validateEvalSuppOne;

        return $this;
    }

    /**
     * Get validateEvalSuppOne
     *
     * @return bool
     */
    public function getValidateEvalSuppOne()
    {
        return $this->validateEvalSuppOne;
    }

    /**
     * Set validateActivityTwo
     *
     * @param boolean $validateActivityTwo
     *
     * @return Student
     */
    public function setValidateActivityTwo($validateActivityTwo)
    {
        $this->validateActivityTwo = $validateActivityTwo;

        return $this;
    }

    /**
     * Get validateActivityTwo
     *
     * @return bool
     */
    public function getValidateActivityTwo()
    {
        return $this->validateActivityTwo;
    }

    /**
     * Set commActivityTwo
     *
     * @param string $commActivityTwo
     *
     * @return Student
     */
    public function setCommActivityTwo($commActivityTwo)
    {
        $this->commActivityTwo = trim(preg_replace('/\s+/', ' ', $commActivityTwo));

        return $this;
    }

    /**
     * Get commActivityTwo
     *
     * @return string
     */
    public function getCommActivityTwo()
    {
        return $this->commActivityTwo;
    }

    /**
     * Set validateEvalSuppTwo
     *
     * @param boolean $validateEvalSuppTwo
     *
     * @return Student
     */
    public function setValidateEvalSuppTwo($validateEvalSuppTwo)
    {
        $this->validateEvalSuppTwo = $validateEvalSuppTwo;

        return $this;
    }

    /**
     * Get validateEvalSuppTwo
     *
     * @return bool
     */
    public function getValidateEvalSuppTwo()
    {
        return $this->validateEvalSuppTwo;
    }

    /**
     * Set observationStudent
     *
     * @param string $observationStudent
     *
     * @return Student
     */
    public function setObservationStudent($observationStudent)
    {
        $this->observationStudent = trim(preg_replace('/\s+/', ' ', $observationStudent));

        return $this;
    }

    /**
     * Get observationStudent
     *
     * @return string
     */
    public function getObservationStudent()
    {
        return $this->observationStudent;
    }

    /**
     * Get promo.
     *
     * @return \AppBundle\Entity\Promo|null
     */
    public function getPromo()
    {
        return $this->promo;
    }
}
