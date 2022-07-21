<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\ImageTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ImageRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Image::class);
        $this->eManager =  $this->getEntityManager();
    }

    /**
     * @param Image $image
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Image $image)
    {
        $this->save($image);
    }


    /**
     * @param Image $image
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Image $image)
    {
        $this->eManager->persist($image);
        $this->eManager->flush();
    }

    public function getImageList($request)
    {
        $query = $this->createQueryBuilder('image')
            ->join('image.imageTags', 'a')
            ->addSelect('a');

        if ($request->get('search')) {
            $query->Where('image.provider_name LIKE :search')
                ->setParameter('search', '%'.$request->get('search').'%');

            $query->orWhere('a.tag LIKE :search_tag')
                ->setParameter('search_tag', '%'.$request->get('search').'%');
        }
        return $query->getQuery();
    }

    public function delete(Image $image){
        $this->getEntityManager()->remove($image);
        $this->getEntityManager()->flush();
    }
}
