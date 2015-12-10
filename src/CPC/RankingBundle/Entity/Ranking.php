<?php

namespace CPC\RankingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CPC\RankingBundle\Repository\RankingRepository")
 * @ORM\Table(name="ranking")
 */
class Ranking
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $scoreEvolution;

     /**
     * @ORM\ManyToOne(targetEntity="CPC\GameBundle\Entity\Game")
     * @ORM\JoinColumn(nullable=false, name="game")
     */
    private $game;

     /**
     * @ORM\ManyToOne(targetEntity="CPC\TeamBundle\Entity\Team")
     * @ORM\JoinColumn(nullable=false, name="team")
     */
    private $team;

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
     * Set scoreEvolution
     *
     * @param integer $scoreEvolution
     *
     * @return Ranking
     */
    public function setScoreEvolution($scoreEvolution)
    {
        $this->scoreEvolution = $scoreEvolution;

        return $this;
    }

    /**
     * Get scoreEvolution
     *
     * @return integer
     */
    public function getScoreEvolution()
    {
        return $this->scoreEvolution;
    }

    /**
     * Set game
     *
     * @param \CPC\GameBundle\Entity\Game $game
     *
     * @return Ranking
     */
    public function setGame(\CPC\GameBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \CPC\GameBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set team
     *
     * @param \CPC\TeamBundle\Entity\Team $team
     *
     * @return Ranking
     */
    public function setTeam(\CPC\TeamBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \CPC\TeamBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
