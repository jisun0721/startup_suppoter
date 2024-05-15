<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * User
 *
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity(repositoryClass="Support\FrontBundle\Entity\UserRepository")
 */
class User implements UserInterface
{    
    const USER_TYPE_EMAIL = 1;
    const USER_TYPE_FACEBOOK = 2;
    const USER_TYPE_NAVER = 3;
    
    public static $ageCode = array(
        0 => "제한없음",
        1 => "10대",
        2 => "20대",
        3 => "30대",
        4 => "40대",
        5 => "50대",
        6 => "60대",
        7 => "70대",
        8 => "80대",
        9 => "90대",
        10 => "100대"
    );
    
    public function __construct() {
        $now = time();
        $this->role = "ROLE_USER";
        $this->device_id = null;
        $this->gcm_token = null;
        $this->created = $now; 
        $this->updated = $now;
        $this->isEnable = false;
        $this->type=1;
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=16, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="naver_id", type="string", length=255, nullable=false)
     */
    private $naver_id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=false)
     */
    private $facebook_id;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=16, nullable=false)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;
    
    /**
     * @var string
     *
     * @ORM\Column(name="device_id", type="string", nullable=true)
     */
    private $device_id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="gcm_token", type="string", nullable=true)
     */
    private $gcm_token;

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
     * Set type
     *
     * @param integer $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set naver_id
     *
     * @param string $naver_id
     *
     * @return User
     */
    public function setNaverId($naver_id)
    {
        $this->naver_id = $naver_id;

        return $this;
    }

    /**
     * Get naver_id
     *
     * @return string
     */
    public function getNaverId()
    {
        return $this->naver_id;
    }

    /**
     * Set facebook_id
     *
     * @param string $facebook_id
     *
     * @return User
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Set age
     *
     * @param integer $age
     *
     * @return User
     */
    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }
    
    /**
     * Get created
     *
     * @return integer
     */
    
    public function getAge()
    {
        return $this->age;
    }
    
    /**
     * Set device_id
     *
     * @param string $device_id
     *
     * @return User
     */
    public function setDeviceId($device_id)
    {
        $this->device_id = $device_id;
        return $this;
    }
    
    /**
     * Get device_id
     *
     * @return string
     */
    
    public function getDeviceId()
    {
        return $this->device_id;
    }
    
    /**
     * Set gcm_token
     *
     * @param string $gcm_token
     *
     * @return User
     */
    public function setGcmToken($gcm_token)
    {
        $this->gcm_token = $gcm_token;
        return $this;
    }
    
    /**
     * Get gcm_token
     *
     * @return string
     */
    
    public function getGcmToken()
    {
        return $this->gcm_token;
    }
    

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return User
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
     * @return User
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
     * @return User
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
    
    public function eraseCredentials() {
        return true;
    }

    public function getRoles() {
        return array($this->role);
        
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

}