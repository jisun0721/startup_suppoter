<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserBusinessType
 *
 * @ORM\Table(name="UserBusinessType", indexes={@ORM\Index(name="fk_UserBookMark_Business_idx", columns={"user_business_id"})})
 * @ORM\Entity(repositoryClass="Support\FrontBundle\Entity\UserBusinessTypeRepository")
 */
class UserBusinessType
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
     * @ORM\Column(name="business_type", type="integer", nullable=false)
     */
    private $business_type;


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
     * @var \Support\FrontBundle\Entity\UserBusiness
     *
     * @ORM\ManyToOne(targetEntity="Support\FrontBundle\Entity\UserBusiness")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_business_id", referencedColumnName="id")
     * })
     */
    private $user_business_id;

    /**
     * Set business_type
     *
     * @param integer $business_type
     *
     * @return UserBusinessType
     */
    public function setBusinessType($business_type)
    {
        $this->business_type = $business_type;

        return $this;
    }

    /**
     * Get business_type
     *
     * @return integer
     */
    public function getBusinessType()
    {
        return $this->business_type;
    }

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return UserBusinessType
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
     * @return UserBusinessType
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
     * @return UserBusinessType
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
     * Set user_business_id
     *
     * @param \Support\FrontBundle\Entity\UserBusiness$user_business_id
     *
     * @return UserBusinessType
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
}

