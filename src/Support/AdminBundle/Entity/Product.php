<?php

namespace Support\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="Product")
 * @ORM\Entity(repositoryClass="Support\AdminBundle\Entity\ProductRepository")
 */
class Product
{
    const PAGE_INFOS = 5;    
    public static $rewardCode = array(	
        0   =>"제한없음",
        1   =>"지원금제공",
        2   =>"장소제공",
        3   =>"교육제공",
        4   =>"멘토링",
    );
    public static $eventCode = array(
        0   =>"제한없음",
        1   =>"박람회",
        2   =>"콘테스트",
        3   =>"지원",
    );

    public static $stateCode = array(	
        0  =>"전국",
        1  =>"서울특별시",
        2  =>"부산광역시",
        3  =>"인천광역시",
        4  =>"대구광역시",
        5  =>"광주광역시",
        6  =>"대전광역시",
        7  =>"울산광역시",
        8  =>"세종특별자치시",
        9  =>"경기도",
        10  =>"강원도",
        11  =>"충청북도",
        12  =>"충청남도",
        13  =>"경상북도",
        14  =>"경상남도",
        15  =>"전라북도",
        16  =>"전라남도",
        17  =>"제주특별자치도",
    );

    public static $areaCode = array(
        0 => "전체",
        100 => "전체", 101 => "강남구", 102 => "강동구", 103 => "강북구", 104 => "강서구", 105 => "관악구", 106 => "광진구", 107 => "구로구", 108 => "금천구", 109 => "노원구", 110 => "도봉구", 111 => "동대문구", 112 => "동작구", 113 => "마포구", 114 => "서대문구", 115 => "서초구", 116 => "성동구", 117 => "성북구", 118 => "송파구", 119 => "양천구", 120 => "영등포구", 121 => "용산구", 122 => "은평구", 123 => "종로구", 124 => "중구", 125 => "중랑구",
        200 => "전체", 201 => "중구", 202 => "서구", 203 => "동구", 204 => "영도구", 205 => "부산진구", 206 => "동래구", 207 => "남구", 208 => "북구", 209 => "강서구", 210 => "해운대구", 211 => "사하구", 212 => "금정구", 213 => "연제구", 214 => "수영구", 215 => "사상구", 216 => "기장군",
        300 => "전체", 301 => "중구", 302 => "동구", 303 => "남구", 304 => "연수구", 305 => "남동구", 306 => "부평구", 307 => "계양구", 308 => "서구", 309 => "강화군", 310 => "옹진군",
        400 => "전체", 401 => "중구", 402 => "동구", 403 => "서구", 404 => "남구", 405 => "북구", 406 => "수성구", 407 => "달서구", 408 => "달성군",
        500 => "전체", 501 => "동구", 502 => "서구", 503 => "남구", 504 => "북구", 505 => "광산구",
        600 => "전체", 601 => "동구", 602 => "중구", 603 => "서구", 604 => "유성구", 605 => "대덕구",
        700 => "전체", 701 => "중구", 702 => "남구", 703 => "동구", 704 => "북구", 705 => "울주군",
        800 => "전체",
        900 => "전체", 901 => "경기남부", 902 => "경기북부", 903 => "수원시", 904 => "성남시", 905 => "안양시", 906 => "부천시", 907 => "안산시", 908 => "용인시", 909 => "광명시", 910 => "평택시", 911 => "과천시", 912 => "오산시", 913 => "시흥시", 914 => "군포시", 915 => "의왕시", 916 => "하남시", 917 => "이천시", 918 => "안성시", 919 => "김포시", 920 => "화성시", 921 => "광주시", 922 => "여주시", 923 => "양평군", 924 => "고양시", 925 => "의정부시", 926 => "동두천시", 927 => "구리시", 928 => "남양주시", 929 => "파주시", 930 => "양주시", 931 => "포천시", 932 => "연천군", 933 => "가평군",
        1000 => "전체", 1001 => "춘천시", 1002 => "원주시", 1003 => "강릉시", 1004 => "동해시", 1005 => "태백시", 1006 => "속초시", 1007 => "삼척시", 1008 => "홍천군", 1009 => "횡성군", 1010 => "영월군", 1011 => "평창군", 1012 => "정선군", 1013 => "철원군", 1014 => "화천군", 1015 => "양구군", 1016 => "인제군", 1017 => "고성군", 1018 => "양양군",
        1100 => "전체", 1101 => "청주시", 1102 => "충주시", 1103 => "제천시", 1104 => "보은군", 1105 => "옥천군", 1106 => "영동군", 1107 => "진천군", 1108 => "괴산군", 1109 => "음성군", 1110 => "단양군", 1111 => "증평군",
        1200 => "전체", 1201 => "천안시", 1202 => "공주시", 1203 => "보령시", 1204 => "아산시", 1205 => "서산시", 1206 => "논산시", 1207 => "계룡시", 1208 => "당진시", 1209 => "금산군", 1210 => "부여군", 1211 => "서천군", 1212 => "청양군", 1213 => "홍성군", 1214 => "예산군", 1215 => "태안군",
        1300 => "전체", 1301 => "포항시", 1302 => "경주시", 1303 => "김천시", 1304 => "안동시", 1305 => "구미시", 1306 => "영주시", 1307 => "영천시", 1308 => "상주시", 1309 => "문경시", 1310 => "경산시", 1311 => "군위군", 1312 => "의성군", 1313 => "청송군", 1314 => "영양군", 1315 => "영덕군", 1316 => "청도군", 1317 => "고령군", 1318 => "성주군", 1319 => "칠곡군", 1320 => "예천군", 1321 => "봉화군", 1322 => "울진군", 1323 => "울릉군",
        1400 => "전체", 1401 => "창원시", 1402 => "진주시", 1403 => "통영시", 1404 => "사천시", 1405 => "김해시", 1406 => "밀양시", 1407 => "거제시", 1408 => "양산시", 1409 => "의령군", 1410 => "함안군", 1411 => "창녕군", 1412 => "고성군", 1413 => "남해군", 1414 => "하동군", 1415 => "산청군", 1416 => "함양군", 1417 => "거창군", 1418 => "합천군",
        1500 => "전체", 1501 => "전주시", 1502 => "군산시", 1503 => "익산시", 1504 => "정읍시", 1505 => "남원시", 1506 => "김제시", 1507 => "완주군", 1508 => "진안군", 1509 => "무주군", 1510 => "장수군", 1511 => "임실군", 1512 => "순창군", 1513 => "고창군", 1514 => "부안군",
        1600 => "전체", 1601 => "목포시", 1602 => "여수시", 1603 => "순천시", 1604 => "나주시", 1605 => "광양시", 1606 => "담양군", 1607 => "곡성군", 1608 => "구례군", 1609 => "고흥군", 1610 => "보성군", 1611 => "화순군", 1612 => "장흥군", 1613 => "강진군", 1614 => "해남군", 1615 => "영암군", 1616 => "무안군", 1617 => "함평군", 1618 => "영광군", 1619 => "장성군", 1620 => "완도군", 1621 => "진도군", 1622 => "신안군",
        1700 => "전체", 1701 => "제주시", 1702 => "서귀포시",
    );
    
    public static $targetCode = array(
        0 => "전체",
        1 => "청소년",
        2 => "대학생",
        3 => "일반인",
        4 => "대학",
        5 => "연구기관",
        6 => "일반기업",
        7 => "1인 창조기업",
    );
    
    public static $overlapCode = array(
        0 => "전체",
        1 => "가능",
        2 => "불가능",
        3 => "조건가능",
    );
    
    public static $businessTypeCode = array(
        0 => "전체",
        1 => "1차산업",
        2 => "2차산업",
        3 => "3차산업",
        4 => "4차산업",
    );
    
    public static $orderCode = array(
        "eventName" => "행사명순",
        "created" => "최신순",
        "readCount" => "조회수순"
    );
    
    public function __construct() { 
        $now = time();
        $this->created = $now;
        $this->updated = $now;
        $this->isEnable = true;
    }
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
     * @ORM\Column(name="main_target", type="string", length=255, nullable=false)
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
     * @ORM\Column(name="summary", type="string", length=255, nullable=false)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=2000, nullable=false)
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(name="business_type",type="string", length=255, nullable=false)
     */
    private $businessType;

    /**
     * @var integer
     *
     * @ORM\Column(name="asset_size", type="integer", nullable=false)
     */
    private $assetSize;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255,  nullable=false)
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
     * @ORM\Column(name="requirement", type="string", length=255, nullable=false)
     */
    private $requirement;

    /**
     * @var string
     *
     * @ORM\Column(name="enquiry", type="string", length=45, nullable=false)
     */
    private $enquiry;

    /**
     * @var string
     *
     * @ORM\Column(name="ori_url", type="string", length=255, nullable=false)
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
     * Set sponsor
     *
     * @param string $sponsor
     *
     * @return Product
     */
    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor
     *
     * @return string
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * Set eventName
     *
     * @param string $eventName
     *
     * @return Product
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set eventType
     *
     * @param integer $eventType
     *
     * @return Product
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return integer
     */
    public function getEventType()
    {
        $eventStr = split(",", $this->eventType);
        
        foreach ($eventStr as $eve)
        {
            $eventArray[] =  self::$eventCode[$eve];
        }
        return $eventArray;
    }

    /**
     * Set recStartTime
     *
     * @param string $recStartTime
     *
     * @return Product
     */
    public function setRecStartTime($recStartTime)
    {
        $this->recStartTime = $recStartTime;

        return $this;
    }

    /**
     * Get recStartTime
     *
     * @return string
     */
    public function getRecStartTime()
    {
        if($this -> recStartTime == 0)
        {
            return 0;
        }
        $time = \DateTime::createFromFormat('y.m.d', $this->recStartTime);
        return $time->format('U');
    }

    /**
     * Set recCloseTime
     *
     * @param string $recCloseTime
     *
     * @return Product
     */
    public function setRecCloseTime($recCloseTime)
    {
        $this->recCloseTime = $recCloseTime;

        return $this;
    }

    /**
     * Get recCloseTime
     *
     * @return string
     */
    public function getRecCloseTime()
    {
        if($this->recCloseTime == 0){
            return 0;
        }
        $time = \DateTime::createFromFormat('y.m.d', $this->recCloseTime);
        return $time->format('U');
    }

    /**
     * Set mainTarget
     *
     * @param string $mainTarget
     *
     * @return Product
     */
    public function setMainTarget($mainTarget)
    {
        $this->mainTarget = $mainTarget;

        return $this;
    }

    /**
     * Get mainTarget
     *
     * @return string
     */
    public function getMainTarget()
    {
        return $this->mainTarget;
    }

    /**
     * Set ageMin
     *
     * @param integer $ageMin
     *
     * @return Product
     */
    public function setAgeMin($ageMin)
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    /**
     * Get ageMin
     *
     * @return integer
     */
    public function getAgeMin()
    {
        return $this->ageMin;
    }

    /**
     * Set ageMax
     *
     * @param integer $ageMax
     *
     * @return Product
     */
    public function setAgeMax($ageMax)
    {
        $this->ageMax = $ageMax;

        return $this;
    }

    /**
     * Get ageMax
     *
     * @return integer
     */
    public function getAgeMax()
    {
        return $this->ageMax;
    }

    /**
     * Set careerMin
     *
     * @param integer $careerMin
     *
     * @return Product
     */
    public function setCareerMin($careerMin)
    {
        $this->careerMin = $careerMin;

        return $this;
    }

    /**
     * Get careerMin
     *
     * @return integer
     */
    public function getCareerMin()
    {
        return $this->careerMin;
    }

    /**
     * Set careerMax
     *
     * @param integer $careerMax
     *
     * @return Product
     */
    public function setCareerMax($careerMax)
    {
        $this->careerMax = $careerMax;

        return $this;
    }

    /**
     * Get careerMax
     *
     * @return integer
     */
    public function getCareerMax()
    {
        return $this->careerMax;
    }

    /**
     * Set rewardType
     *
     * @param string $rewardType
     *
     * @return Product
     */
    public function setRewardType($rewardType)
    {
        $this->rewardType = $rewardType;

        return $this;
    }

    /**
     * Get rewardType
     *
     * @return string
     */
    public function getRewardType()
    {
       $rewardStr = split(",", $this->rewardType);
        foreach($rewardStr as $reward)
       {
            $rewardArray[] = self::$rewardCode[$reward];
       }
        return $rewardArray;
    }

    /**
     * Set overlapType
     *
     * @param integer $overlapType
     *
     * @return Product
     */
    public function setOverlapType($overlapType)
    {
        $this->overlapType = $overlapType;

        return $this;
    }

    /**
     * Get overlapType
     *
     * @return integer
     */
    public function getOverlapType()
    {
        return $this->overlapType;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Product
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return Product
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set businessType
     *
     * @param string $businessType
     *
     * @return Product
     */
    public function setBusinessType($businessType)
    {
        $this->businessType = $businessType;

        return $this;
    }

    /**
     * Get businessType
     *
     * @return string
     */
    public function getBusinessType()
    {
        $businessStr = split(",", $this->businessType);
        return $businessStr;
    }

    /**
     * Set assetSize
     *
     * @param integer $assetSize
     *
     * @return Product
     */
    public function setAssetSize($assetSize)
    {
        $this->assetSize = $assetSize;
        return $this;
    }

    /**
     * Get assetSize
     *
     * @return integer
     */
    public function getAssetSize()
    {
        return $this->assetSize;
    }

    /**
     * Set requirement
     *
     * @param string $requirement
     *
     * @return Product
     */
    public function setRequirement($requirement)
    {
        $this->requirement = $requirement;

        return $this;
    }

    /**
     * Get requirement
     *
     * @return string
     */
    public function getRequirement()
    {
        return $this->requirement;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Product
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
        $stateStr= split(",", $this->state);        
        
        foreach($stateStr as $state){
            $stateArray[] = self::$stateCode[(int)$state];
        }
        return $stateArray;         
    }

    /**
     * Set area
     *
     * @param string $area
     *
     * @return Product
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
        $areaStr= split(",", $this->area);                      
/*        foreach($areaStr as $area){
            $areaArray[] = self::$areaCode[(int)$area];
        }*/
        return $areaStr;
        
    }

    /**
     * Set enquiry
     *
     * @param string $enquiry
     *
     * @return Product
     */
    public function setEnquiry($enquiry)
    {
        $this->enquiry = $enquiry;

        return $this;
    }

    /**
     * Get enquiry
     *
     * @return string
     */
    public function getEnquiry()
    {
        return $this->enquiry;
    }

    /**
     * Set oriUrl
     *
     * @param string $oriUrl
     *
     * @return Product
     */
    public function setOriUrl($oriUrl)
    {
        $this->oriUrl = $oriUrl;

        return $this;
    }

    /**
     * Get oriUrl
     *
     * @return string
     */
    public function getOriUrl()
    {
        return $this->oriUrl;
    }

    /**
     * Set readCount
     *
     * @param integer $readCount
     *
     * @return Product
     */
    public function setReadCount($readCount)
    {
        $this->readCount = $readCount;

        return $this;
    }
    
    /**
     * Get readCount
     *
     * @return integer
     */
    public function getReadCount()
    {
        return $this->readCount;
    }

    /**
     * Set created
     *
     * @param integer $created
     *
     * @return Product
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
     * @return Product
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
     * @return Product
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

