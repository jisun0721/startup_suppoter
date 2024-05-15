<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userbusiness
 *
 * @ORM\Table(name="UserBusiness", uniqueConstraints={@ORM\UniqueConstraint(name="user_id_UNIQUE", columns={"user_id"})}, indexes={@ORM\Index(name="fk_UserBusiness_User1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Support\FrontBundle\Entity\UserBusinessRepository")
 */
class UserBusiness
{
    
    public function __construct() { 
        $now = time();
        $this->created = $now;
        $this->updated = $now;
        $this->isEnable = true;
    }

    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var float
     *
     * @ORM\Column(name="asset_size", type="float", nullable=false)
     */
    private $asset_size;

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
     * @var \Support\FrontBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Support\FrontBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Set asset_size
     *
     * @param float $asset_size
     *
     * @return UserBusiness
     */
    public function setAssetSize($asset_size)
    {
        $this->asset_size = $asset_size;

        return $this;
    }

    /**
     * Get asset_size
     *
     * @return float
     */
    public function getAssetSize()
    {
        return $this->asset_size;
    }

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return UserBusiness
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
     * @return UserBusiness
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
     * @return UserBusiness
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

    /**
     * Set user
     *
     * @param \Support\FrontBundle\Entity\User $user
     *
     * @return UserBusiness
     */
    public function setUser(\Support\FrontBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Support\FrontBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

