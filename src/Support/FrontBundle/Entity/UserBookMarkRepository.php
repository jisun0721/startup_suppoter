<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserBookMarkRepository  extends EntityRepository {

    public function loadUserBookMark($productId, $userId){
        $query = $this->createQueryBuilder('b')
                ->where('b.user = :userId')
                ->andwhere('b.mark_product = :productId')
                ->andwhere('b.isEnable = true')
                ->setParameter('productId', $productId)
                ->setParameter('userId', $userId)
                ->getQuery();
        $id = $query->getOneOrNullResult();
        
        return $id;
    }
    
    public function findUserForMark($userId)
    {
        $query = $this->createQueryBuilder('b')
                ->where('b.user = :userId')
                ->andwhere('b.isEnable = true')
                ->setParameter('userId', $userId)
                ->getQuery();
        $bookmarks = $query->getResult();
        
        $products = array();

        foreach($bookmarks as $value)
        {
            $sub_query = $this->getEntityManager()->getRepository("SupportAdminBundle:Product")
                ->createQueryBuilder('p')
                ->where('p.id = :mark_product')
                ->andwhere('p.isEnable = true')
                ->setParameter('mark_product', $value->getMarkProduct())
                ->getQuery();
            
            array_push($products, $sub_query->getOneOrNullResult());
        }
        
        return $products;
    }
}
