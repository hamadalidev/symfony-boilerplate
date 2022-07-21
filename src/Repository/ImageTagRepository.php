<?php

namespace App\Repository;

use App\Entity\ImageTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ImageTagRepository extends ServiceEntityRepository
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
        parent::__construct($registry, ImageTag::class);
        $this->eManager =  $this->getEntityManager();
    }

    /**
     * @param ImageTag $imageTag
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(ImageTag $imageTag)
    {
        $this->save($imageTag);
    }


    /**
     * @param ImageTag $imageTag
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ImageTag $imageTag)
    {
        $this->eManager->persist($imageTag);
        $this->eManager->flush();
    }
}
