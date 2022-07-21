<?php

namespace App\Controller;

use App\Custom\IsPermission;
use App\Entity\Image;
use App\Exception\ValidationException;
use App\Manager\ImageManager;
use App\Custom\RequestValidation;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Zend\Diactoros\Response\JsonResponse as Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;


class ImageController extends AbstractController
{
    use RequestValidation;
    use IsPermission;
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @Route("/api/image", name="app_image_list", methods={"GET"})
     *
     * @OA\Response(
     *     response=200,
     *     description="{     'data': [         {             'id': 1,             'image_url': ''/files/post/62c6d363af3d6.png'',             'provider_name': 'provider name1',             'createdAt': '2022-07-07T17:36:51+05:00',             'imageTags': [                 {                     'id': 1,                     'tag': 'ds',                     'createdAt': '2022-07-07T17:36:51+05:00'                 },                 {                     'id': 2,                     'tag': 'atg2',                     'createdAt': '2022-07-07T17:36:51+05:00'                 }             ]         },     ],     'current_page': '1',     'next_page': null,     'previous_page': null,     'per_page': 10,     'total': 1 }",
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="search",
     *     in="query",
     *     description="search keyword, it search in provider name and image tag",
     *     @OA\Schema(type="string")
     * )
     *
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->imageManager->getImageList($request);
        return $this->json($data);
    }

    /**
     * @Route("/api/image/delete/{id}", name="app_image_delete", methods={"GET"})
     * @ParamConverter(name="image", class="App\Entity\Image", options={
     *     "id" = "id"
     * })
     *
     * * @OA\Response(
     *     response=200,
     *     description="Image delete successfully",
     * )
     */
    public function delete(Image $image,Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->validateAdminRequest($user, 'Admin')) {
            return $this->json([
                'success' => false,
                'error' => "You don't have access"
            ], 403);
        }


        $this->imageManager->delete($image);
        return $this->json([
            'status' => true,
            'message' => 'Image delete successfully',
        ]);
    }

    /**
     * @Route("/api/image", name="app_image_save", methods={"POST"})
     *  * @OA\Response(
     *     response=200,
     *     description="User test2 successfully created",
     * )
     * @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      type="file",
     *                      description="image"
     *                  ),
     *                  @OA\Property(
     *                      property="provider_name",
     *                      type="string",
     *                      description="image provider name"
     *                  ),
     *                  @OA\Property(
     *                      property="tag",
     *                      type="array",
     *                      @OA\Items(
                         *          type="array",
                         *          @OA\Items()
                         *      ),
     *                      description="image tag"
     *                  ),
     *
     *     @OA\Property(
     *                      property="with_url",
     *                      type="string",
     *                      description="pass true if you want to upload image with url other wise send false of not send"
     *                  ),
     *
     *      @OA\Property(
     *                      property="url",
     *                      type="string",
     *                      description="image live url"
     *                  ),
     *
     *
     *              )
     *          )
     * )
     */
    public function saveImage(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $user = $this->getUser();
        if (!$this->validateAdminRequest($user, 'Admin')) {
            return $this->json([
                'success' => false,
                'error' => "You don't have access"
            ], 403);
        }

        $withUrl = $request->request->get("with_url", false);
        try {
        $input = [
            'provider_name'         => $request->request->get("provider_name"),
            'tag'         => $request->request->get("tag"),
            'image'         => $request->files->get('image'),

        ];
        $validatorRule = [
            'provider_name'         => [new Assert\NotBlank],
            'tag'         => [new Assert\NotBlank],
            'image'         => [new Assert\NotBlank, new Assert\Image()],
        ];

        if ($withUrl == true) {
            $validatorRule['image'] = [new Assert\NotBlank, new Assert\Url()];
            $input['image'] = $request->request->get('url');
        }
        $constraints = new Assert\Collection($validatorRule);
        $this->validate($input, $constraints, $validator);
        $input['with_url'] = $withUrl;

            if ($withUrl == true) {
                if (!$this->validImage($input['image'])) {
                    return $this->json([
                        'success'       => false,
                        'violations'    => 'image url is not valid'
                    ], 400);
                }
            }

            $this->imageManager->saveImage($input);

        } catch (ValidationException $ex) {
            return $this->json([
                'success'       => false,
                'error'    => $ex->getViolations()
            ], $ex->getCode());
        } catch (\Exception $ex) {
            return $this->json([
                'success'       => false,
                'violations'    => $ex->getMessage()
            ], 400);
        }


        return $this->json([
            'status' => true,
            'message' => 'Image save successfully',
        ]);
    }

    public function validImage($url) {
        if (preg_match('/\.(jpeg|jpg|png|gif)$/i', $url)) {
         return true;
        }
        return false;
    }
}

