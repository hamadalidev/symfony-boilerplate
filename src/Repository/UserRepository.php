<?php

namespace App\Repository;

use App\Entity\Permission;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $eManager;

    /**
     * PermissionRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->eManager =  $this->getEntityManager();
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(User $user)
    {
        $this->save($user);
    }


    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user)
    {
        $this->eManager->persist($user);
        $this->eManager->flush();
    }

    public function findUser($request)
    {
        return $this->createQueryBuilder('user')
            ->Where('user.email = :email')
            ->setParameter('email', $request->get('email'))
            ->orWhere('user.username = :username')
            ->setParameter('username', $request->get('username'))
            ->getQuery()->getResult();
    }
}
