<?php

namespace CPC\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CPC\TeamBundle\Repository\TeamRepository")
 * @ORM\Table(name="team")
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentScore;

    /**
     * @ORM\OneToMany(targetEntity="CPC\PlayerBundle\Entity\Player", mappedBy="team")
     * @ORM\JoinColumn(name="players")
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity="CPC\VideoGameBundle\Entity\VideoGame", inversedBy="teams")
     * @ORM\JoinColumn(nullable=false, name="videogame")
     */
    private $videogame;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Team
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

    /**
     * Set currentScore
     *
     * @param integer $currentScore
     *
     * @return Team
     */
    public function setCurrentScore($currentScore)
    {
        $this->currentScore = $currentScore;

        return $this;
    }

    /**
     * Get currentScore
     *
     * @return integer
     */
    public function getCurrentScore()
    {
        return $this->currentScore;
    }

    /**
     * Add player
     *
     * @param \CPC\PlayerBundle\Entity\Player $player
     *
     * @return Team
     */
    public function addPlayer(\CPC\PlayerBundle\Entity\Player $player)
    {
        $this->players[] = $player;

        return $this;
    }

    /**
     * Remove player
     *
     * @param \CPC\PlayerBundle\Entity\Player $player
     */
    public function removePlayer(\CPC\PlayerBundle\Entity\Player $player)
    {
        $this->players->removeElement($player);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set videogame
     *
     * @param \CPC\VideoGameBundle\Entity\VideoGame $videogame
     *
     * @return Team
     */
    public function setVideogame(\CPC\VideoGameBundle\Entity\VideoGame $videogame = null)
    {
        $this->videogame = $videogame;

        return $this;
    }

    /**
     * Get videogame
     *
     * @return \CPC\VideoGameBundle\Entity\VideoGame
     */
    public function getVideogame()
    {
        return $this->videogame;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
