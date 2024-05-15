<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="Product")
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor", type="string", length=45, nullable=false)
     */
    private $sponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=255, nullable=false)
     */
    private $eventName;

    /**
     * @var integer
     *
     * @ORM\Column(name="event_type", type="integer", nullable=false)
     */
    private $eventType;

    /**
     * @var string
     *
     * @ORM\Column(name="rec_start_time", type="string", length=45, nullable=false)
     */
    private $recStartTime;

    /**
     * @var string
     *
     * @ORM\Column(name="rec_close_time", type="string", length=45, nullable=false)
     */
    private $recCloseTime;

    /**
     * @var string
     *
     * @ORM\Column(name="main_target", type="text", length=65535, nullable=false)
     */
    private $mainTarget;

    /**
     * @var integer
     *
     * @ORM\Column(name="age_min", type="integer", nullable=false)
     */
    private $ageMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="age_max", type="integer", nullable=false)
     */
    private $ageMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="career_min", type="integer", nullable=false)
     */
    private $careerMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="career_max", type="integer", nullable=false)
     */
    private $careerMax;

    /**
     * @var string
     *
     * @ORM\Column(name="reward_type", type="string", length=255, nullable=false)
     */
    private $rewardType;

    /**
     * @var integer
     *
     * @ORM\Column(name="overlap_type", type="integer", nullable=false)
     */
    private $overlapType;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=1000, nullable=false)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text", length=65535, nullable=false)
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="business_type", type="string", length=255, nullable=false)
     */
    private $businessType;

    /**
     * @var integer
     *
     * @ORM\Column(name="asset_size", type="bigint", nullable=false)
     */
    private $assetSize;

    /**
     * @var string
     *
     * @ORM\Column(name="requirement", type="string", length=2000, nullable=false)
     */
    private $requirement;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=false)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=255, nullable=false)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="enquiry", type="text", length=65535, nullable=false)
     */
    private $enquiry;

    /**
     * @var string
     *
     * @ORM\Column(name="ori_url", type="text", length=65535, nullable=false)
     */
    private $oriUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="read_count", type="integer", nullable=false)
     */
    private $readCount;

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
    private $isenable;


}

