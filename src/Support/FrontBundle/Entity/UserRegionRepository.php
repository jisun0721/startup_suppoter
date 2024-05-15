<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRegionRepository extends EntityRepository{
    
    public function loadUserRegion($userBusinessId){
        $query = $this->createQueryBuilder('q')
                ->where('q.user_business_id = :userBusinessId')
                ->setParameter('userBusinessId', $userBusinessId)
                ->getQuery();
        
        $userRegion = $query->getOneOrNullResult();
        
        return $userRegion;
    }
    
}
