<?php

namespace CPC\VideoGameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="videogame")
 */
class VideoGame
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
     * @ORM\Column(type="boolean")
     */
    private $isSolo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasAPI;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $banner;

    /**
     * @ORM\OneToMany(targetEntity="CPC\TeamBundle\Entity\Team", mappedBy="videogame")
     * @ORM\JoinColumn(name="teams")
     */
    private $teams;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return VideoGame
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
     * Set isSolo
     *
     * @param boolean $isSolo
     *
     * @return VideoGame
     */
    public function setIsSolo($isSolo)
    {
        $this->isSolo = $isSolo;

        return $this;
    }

    /**
     * Get isSolo
     *
     * @return boolean
     */
    public function getIsSolo()
    {
        return $this->isSolo;
    }

    /**
     * Set hasAPI
     *
     * @param boolean $hasAPI
     *
     * @return VideoGame
     */
    public function setHasAPI($hasAPI)
    {
        $this->hasAPI = $hasAPI;

        return $this;
    }

    /**
     * Get hasAPI
     *
     * @return boolean
     */
    public function getHasAPI()
    {
        return $this->hasAPI;
    }

    /**
     * Add team
     *
     * @param \CPC\TeamBundle\Entity\Team $team
     *
     * @return VideoGame
     */
    public function addTeam(\CPC\TeamBundle\Entity\Team $team)
    {
        $this->teams[] = $team;

        return $this;
    }

    /**
     * Remove team
     *
     * @param \CPC\TeamBundle\Entity\Team $team
     */
    public function removeTeam(\CPC\TeamBundle\Entity\Team $team)
    {
        $this->teams->removeElement($team);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set banner
     *
     * @param string $banner
     *
     * @return VideoGame
     */
    public function setBanner($banner)
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Get banner
     *
     * @return string
     */
    public function getBanner()
    {
        return $this->banner;
    }
}
