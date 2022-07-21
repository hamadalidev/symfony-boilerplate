<?php

namespace App\Manager;

use App\Custom\FileUploader;
use App\Entity\Image;
use App\Entity\ImageTag;
use App\Repository\ImageRepository;
use App\Repository\ImageTagRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ImageManager
{
    /**
     * @var ImageRepository
     */
    private $imageRepository;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var ImageTagRepository
     */
    private $imageTagRepository;

    /**
     * @param ImageRepository $imageRepository
     * @param FileUploader $fileUploader
     * @param ImageTagRepository $imageTagRepository
     */
    public function __construct(ImageRepository $imageRepository,
                                FileUploader $fileUploader,
    ImageTagRepository $imageTagRepository)
    {
        $this->imageRepository = $imageRepository;
        $this->fileUploader = $fileUploader;
        $this->imageTagRepository = $imageTagRepository;
    }

    public function saveImage($data)
    {
        $url = Image::IMAGE_FILES_URL;
        $clientFilename = '';
        if ($data['with_url']) {
            $content = file_get_contents($data['image']);
            $path = Image::IMAGE_FILES_PATH .'/' .time() . ' '.basename($data['image']);
            $url = Image::IMAGE_FILES_URL .'/' .time() . ' '.basename($data['image']);
            file_put_contents($path, $content);
        } else {
            $this->fileUploader->upload(static function (string $filename, string $originalName) use (&$url, &$clientFilename) {
                $url .= '/' . $filename;
                $clientFilename = $originalName;
            }, Image::IMAGE_FILES_PATH, $data['image']);
        }

        $image = new Image();
        $image->setImageUrl($url);
        $image->setProviderName($data['provider_name']);
        $this->imageRepository->save($image);

        foreach ($data['tag'] as $tag) {
            if (!empty($tag)) {
                $imageTag = new ImageTag();
                $imageTag->setTag($tag);
                $imageTag->image = $image;
                $this->imageTagRepository->save($imageTag);
            }
        }
    }

    public function getImageList($request)
    {
        $qb = $this->imageRepository->getImageList($request);
        $paginator = new Paginator($qb);
        $totalItem = count($paginator);
        $currentPage = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);
        $totalPageCount = ceil($totalItem/2);

        $nextPage = (($currentPage < $totalPageCount) ? $currentPage +1: null);
        $previusPage = (($currentPage > 1) ?$currentPage - 1: null);

        $pagination = $paginator->getQuery()
            ->setFirstResult($perPage*($currentPage-1))
            ->setMaxResults($perPage)
            ->getResult(Query::HYDRATE_ARRAY);

        return [
            'data' => $pagination,
            'current_page'=> $currentPage,
            'next_page'=> $nextPage,
            'previous_page'=> $previusPage,
            'per_page'=> $perPage,
            'total' => $totalItem,
        ];
    }

    public function delete(Image $image)
    {
        $this->imageRepository->delete($image);
    }

}
