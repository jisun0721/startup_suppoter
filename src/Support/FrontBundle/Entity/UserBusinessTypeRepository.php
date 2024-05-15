<?php

namespace Support\FrontBundle\Entity;
use Doctrine\ORM\EntityRepository;

class UserBusinessTypeRepository extends EntityRepository {
    
    public function loadUserBusinessType($userBusinessId){
        $query = $this->createQueryBuilder('q')
                ->where('q.user_business_id = :userBusinessId')
                ->setParameter('userBusinessId', $userBusinessId)
                ->getQuery();
        
        $UserBusinessType = $query->getOneOrNullResult();
        
        return $UserBusinessType;
    }
}
