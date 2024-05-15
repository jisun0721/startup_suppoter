<?php

namespace Support\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    
    public function loadUserByUsername($email) {
         
        $query = $this->createQueryBuilder('u')
                ->where('u.email = :email')
                ->andWhere('u.isEnable = 1')
                ->setParameter('email', $email)                
                ->getQuery();
        try{
            $user = $query->getOneOrNullResult();

        }  catch (\NoResultException $e){
            throw new UsernameNotFoundException(
                    sprintf('Unable to find an active admin SupportFrontBundle:User object identified by "%s".', $name),
                    null, 0, $e);
        }
                
        return $user;
    }
        

    public function refreshUser(UserInterface $user) {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }              
        return $this->loadUserByUsername($user->getEmail());
    }

    public function supportsClass($class) {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    
    }
    
    
    public function findForAdmins($start, $limit, $order, $asc){
        $qb = $this->createQueryBuilder("u")            
    //            ->select("COUNT(q.id) as qnaCount, u")
    //            ->leftJoin("PristoAdminBundle:Qna", "q", "WITH", "u.id = q.userId")
                ->orderBy("u.".$order, $asc)
    //            ->groupBy("q.userId")
                ->setFirstResult($start)
                ->setMaxResults($limit)
                ->getQuery();    
        
        try{
            $users = $qb->getResult();            
        }catch (\NoResultException $e){
              throw new UsernameNotFoundException(
                        sprintf('Unable to find an active admin PristoFrontBundle:User object identified by "%s".', $name),
                        null, 0, $e);        
        }        
         return $users;
    }

    public function maxPage($max){
        $users = $this->findAll();        
        return floor(count($users) / $max);
    }
    
}