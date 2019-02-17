<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RocketChannel
 *
 * @ORM\Table(name="rocket_channel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RocketChannelRepository")
 */
class RocketChannel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idChannel", type="integer")
     */
    private $idChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="nameChannel", type="string", length=255)
     */
    private $nameChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="ownerName", type="string", length=255)
     */
    private $ownerName;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudoOwner", type="string", length=255)
     */
    private $pseudoOwner;


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
     * Set idChannel.
     *
     * @param int $idChannel
     *
     * @return RocketChannel
     */
    public function setIdChannel($idChannel)
    {
        $this->idChannel = $idChannel;

        return $this;
    }

    /**
     * Get idChannel.
     *
     * @return int
     */
    public function getIdChannel()
    {
        return $this->idChannel;
    }

    /**
     * Set nameChannel.
     *
     * @param string $nameChannel
     *
     * @return RocketChannel
     */
    public function setNameChannel($nameChannel)
    {
        $this->nameChannel = $nameChannel;

        return $this;
    }

    /**
     * Get nameChannel.
     *
     * @return string
     */
    public function getNameChannel()
    {
        return $this->nameChannel;
    }

    /**
     * Set nameUser.
     *
     * @param string $ownerName
     *
     * @return RocketChannel
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    /**
     * Get ownerName.
     *
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * Set pseudoOwner.
     *
     * @param string $pseudoOwner
     *
     * @return RocketChannel
     */
    public function setPseudoOwner($pseudoOwner)
    {
        $this->pseudoOwner = $pseudoOwner;

        return $this;
    }

    /**
     * Get pseudoOwner.
     *
     * @return string
     */
    public function getPseudoOwner()
    {
        return $this->pseudoOwner;
    }
}
