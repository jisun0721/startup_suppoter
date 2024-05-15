<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userregion
 *
 * @ORM\Table(name="UserRegion", indexes={@ORM\Index(name="fk_UserRegion_UserBusiness_idx", columns={"user_business_id"})})
 * @ORM\Entity(repositoryClass="Support\FrontBundle\Entity\UserRegionRepository")
 */
class UserRegion
{
    
        public function __construct() { 
        $now = time();
        $this->created = $now;
        $this->updated = $now;
        $this->isEnable = true;
    }

    /**
     * @var \Support\FrontBundle\Entity\UserBusiness
     *
     * @ORM\ManyToOne(targetEntity="Support\FrontBundle\Entity\UserBusiness")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_business_id", referencedColumnName="id")
     * })
     */
    private $user_business_id;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=45, nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=45, nullable=false)
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\Column(name="created", type="integer", nullable=false)
     */
    private $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated", type="integer", nullable=false)
     */
    private $updated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isEnable", type="boolean", nullable=false)
     */
    private $isEnable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set user_business_id
     *
     * @param \Support\FrontBundle\Entity\UserBusiness$user_business_id
     *
     * @return UserRegion
     */
    public function setUserBusinessId($user_business_id)
    {
        $this->user_business_id = $user_business_id;

        return $this;
    }

    /**
     * Get user_business_id
     *
     * @return \Support\FrontBundle\Entity\UserBusiness
     */
    public function getUserBusinessId()
    {
        return $this->user_business_id;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return UserRegion
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set area
     *
     * @param string $area
     *
     * @return UserRegion
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return UserRegion
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param integer $updated
     *
     * @return UserRegion
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return integer
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set isEnable
     *
     * @param boolean $isEnable
     *
     * @return UserRegion
     */
    public function setIsEnable($isEnable)
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    /**
     * Get isEnable
     *
     * @return boolean
     */
    public function getIsEnable()
    {
        return $this->isEnable;
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
}

