<?php
/**
 * Created by PhpStorm.
 * User: DarkoKlisuric
 * Date: 24.7.2019.
 * Time: 15:27
 */

namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/likes")
 * @package App\Controller
 */
class LikesController extends Controller
{
    /**
     * @Route("/like/{id}" , name="likes_like")
     */
    public function like(MicroPost $microPost)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User){
            return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
        }
        $microPost->like($currentUser);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' =>$microPost->getLikedBy()->count()
        ]);
    }

    /**
     * @Route("/unlike/{id}" , name="likes_unlike")
     */
    public function unlike(MicroPost $microPost)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if(!$currentUser instanceof User){
            return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
        }
        $microPost->getLikedBy()->removeElement($currentUser);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            'count' =>$microPost->getLikedBy()->count()
        ]);
    }
}