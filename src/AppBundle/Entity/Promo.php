<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promo
 *
 * @ORM\Table(name="promo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PromoRepository")
 */
class Promo
{

    public function __toString()
    {
        return $this->getName();
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City", inversedBy="promo")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="langage", type="string", length=255)
     */
    private $langage;

    /**
     * @var Student
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Student", mappedBy="promo")
     */
    private $students;

    /**
     * @var string
     *
     * @ORM\Column(name="trainer", type="string", length=255, nullable=true)
     */
    private $trainer;

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Promo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set langage.
     *
     * @param string $langage
     *
     * @return Promo
     */
    public function setLangage($langage)
    {
        if ($langage == 'Symfony' || $langage == 'PHP'){
            $this->langage = 'php';
        } elseif ($langage == 'AngularJS' || $langage == 'JS' || $langage == 'React' || $langage == 'Angular'){
            $this->langage = 'js';
        } elseif ($langage == 'Java JEE' || $langage == 'Android'){
            $this->langage = 'java';
        } else{
            $this->langage = 'undifined';
        }
        return $this;
    }

    /**
     * Get langage.
     *
     * @return string
     */
    public function getLangage()
    {
        return $this->langage;
    }

    /**
     * Set city.
     *
     * @param \AppBundle\Entity\City|null $city
     *
     * @return Promo
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return \AppBundle\Entity\City|null
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add student.
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Promo
     */
    public function addStudent(\AppBundle\Entity\Student $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student.
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeStudent(\AppBundle\Entity\Student $student)
    {
        return $this->students->removeElement($student);
    }

    /**
     * Get students.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @return string
     */
    public function getTrainer()
    {
        return $this->trainer;
    }

    /**
     * @param string $trainer
     */
    public function setTrainer($trainer)
    {
        $this->trainer = $trainer;
    }
}
