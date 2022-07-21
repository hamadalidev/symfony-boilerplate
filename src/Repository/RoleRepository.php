<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoleRepository extends ServiceEntityRepository
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $eManager;

    /**
     * RoleRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
        $this->eManager =  $this->getEntityManager();
    }

    /**
     * @param Role $role
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Role $role)
    {
        $this->save($role);
    }

    /**
     * @param Role $role
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(Role $role)
    {
        $this->eManager->remove($role);
        $this->eManager->flush();
    }


    /**
     * @param Role $role
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Role $role)
    {
        $this->eManager->persist($role);
        $this->eManager->flush();
    }

    public function findByIds(array $ids)
    {
        return $this->createQueryBuilder('r')
            ->where('r.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}
