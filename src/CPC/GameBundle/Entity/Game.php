<?php

namespace CPC\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $winningTeam;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="CPC\TeamBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false, name="team1")
     */
    private $team1;

    /**
     * @ORM\ManyToOne(targetEntity="CPC\TeamBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false, name="team2")
     */
    private $team2;

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
     * Set winningTeam
     *
     * @param boolean $winningTeam
     *
     * @return Game
     */
    public function setWinningTeam($winningTeam)
    {
        $this->winningTeam = $winningTeam;

        return $this;
    }

    /**
     * Get winningTeam
     *
     * @return boolean
     */
    public function getWinningTeam()
    {
        return $this->winningTeam;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Game
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
     * Set team1
     *
     * @param \CPC\TeamBundle\Entity\Team $team1
     *
     * @return Game
     */
    public function setTeam1(\CPC\TeamBundle\Entity\Team $team1 = null)
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * Get team1
     *
     * @return \CPC\TeamBundle\Entity\Team
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * Set team2
     *
     * @param \CPC\TeamBundle\Entity\Team $team2
     *
     * @return Game
     */
    public function setTeam2(\CPC\TeamBundle\Entity\Team $team2 = null)
    {
        $this->team2 = $team2;

        return $this;
    }

    /**
     * Get team2
     *
     * @return \CPC\TeamBundle\Entity\Team
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    public function getWinner()
    {
        if ($winningTeam === 0)
        {
            return $team1;
        }

        return $team2;
    }
    public function getLoser()
    {
        if ($winningTeam === 1)
        {
            return $team2;
        }

        return $team1;
    }
}
