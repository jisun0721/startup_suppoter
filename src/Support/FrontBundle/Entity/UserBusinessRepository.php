<?php

namespace Support\FrontBundle\Entity;
use Doctrine\ORM\EntityRepository;

class UserBusinessRepository extends EntityRepository{
    
    public function loadUserBusinessByUser($userId){
        $query = $this->createQueryBuilder('q')
                ->where('q.user = :userId')
                ->setParameter('userId', $userId)
                ->getQuery();
        
        $profiles = $query->getResult();
        
        return $profiles;
    }
    
}
