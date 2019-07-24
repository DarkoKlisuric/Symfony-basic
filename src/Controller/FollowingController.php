<?php
/**
 * Created by PhpStorm.
 * User: DarkoKlisuric
 * Date: 24.7.2019.
 * Time: 11:26
 */

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 * Class FollowingController
 * @package App\Controller
 */
class FollowingController  extends Controller
{
    /**
     * @Route("/follow/{id}" , name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();
        if($userToFollow->getId() !== $currentUser->getId()){
            
            $currentUser->getFollowing()->add($userToFollow);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('micro_post_user',
                ['username' => $userToFollow->getUsername()]);
        }
    }

    /**
     * @Route("/unfollow/{id}" , name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();

        $currentUser->getFollowing()->removeElement($userToUnfollow);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('micro_post_user',
            ['username' => $userToUnfollow->getUsername()]);


    }
}