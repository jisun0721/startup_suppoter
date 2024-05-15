<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ReplyRepository extends EntityRepository{
    
    public function loadReplyByProduct($product_id){
        $query = $this->createQueryBuilder('b')
                ->where('b.product_id = :product_Id')
                ->setParameter('product_Id', $product_id)
                ->getQuery();
        
        $replys = $query->getResult();
        return $replys;
    }
    
}
