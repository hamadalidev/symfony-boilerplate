<?php

namespace App\Repository;

use App\Entity\Permission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PermissionRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Permission::class);
        $this->eManager =  $this->getEntityManager();
    }

    /**
     * @param Permission $permission
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Permission $permission)
    {
        $this->save($permission);
    }


    /**
     * @param Permission $permission
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Permission $permission)
    {
        $this->eManager->persist($permission);
        $this->eManager->flush();
    }
}
