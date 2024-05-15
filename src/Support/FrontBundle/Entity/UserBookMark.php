<?php

namespace Support\FrontBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserBookMark
 *
 * @ORM\Table(name="UserBookMark", indexes={@ORM\Index(name="fk_BookMarkeUser_Product1_idx", columns={"mark_product_id"}), @ORM\Index(name="fk_UserBookMark_User_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Support\FrontBundle\Entity\UserBookMarkRepository")
 */
class UserBookMark
{
    
    public function __construct() {
        $now = time();
        $this->setCreated($now);
        $this->setUpdated($now);
        $this->setIsEnable(true);
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
     * @var \Support\FrontBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Support\FrontBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Support\AdminBundle\Entity\Product
     *
     * @ORM\ManyToOne(targetEntity="Support\AdminBundle\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mark_product_id", referencedColumnName="id")
     * })
     */
    private $mark_product;

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
     * Set created
     *
     * @param integer $created
     *
     * @return UserBookMark
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
     * @return UserBookMark
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
     * @return UserBookMark
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
     * @return UserBookMark
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

    /**
     * Set mark_product
     *
     * @param \Support\AdminBundle\Entity\Product $mark_product
     *
     * @return UserBookMark
     */
    public function setMarkProduct(\Support\AdminBundle\Entity\Product $mark_product = null)
    {
        $this->mark_product = $mark_product;

        return $this;
    }

    /**
     * Get mark_product
     *
     * @return \Support\AdminBundle\Entity\Product
     */
    public function getMarkProduct()
    {
        return $this->mark_product;
    }
}

