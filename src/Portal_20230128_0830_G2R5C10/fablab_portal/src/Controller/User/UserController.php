<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : UserController.php - date : 03 oct. 2021
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/profile",
     *     "fr": "/profil"
     * }, name="profile",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function profile(UserInterface $user): Response
    {
        //dd($user);
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
