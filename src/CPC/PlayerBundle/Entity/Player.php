<?php

namespace CPC\PlayerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CPC\PlayerBundle\Repository\PlayerRepository")
 * @ORM\Table(name="player")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nickname;

    /**
     * @ORM\ManyToOne(targetEntity="CPC\TeamBundle\Entity\Team", inversedBy="players")
     * @ORM\JoinColumn(nullable=false, name="team")
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity="CPC\UserBundle\Entity\User", inversedBy="players")
     * @ORM\JoinColumn(nullable=false, name="user")
     */
    protected $user;

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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Player
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set team
     *
     * @param \CPC\TeamBundle\Entity\Team $team
     *
     * @return Player
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

    /**
     * Set user
     *
     * @param \CPC\UserBundle\Entity\User $user
     *
     * @return Player
     */
    public function setUser(\CPC\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CPC\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
