<?php
/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : Security/SecurityController.php - date : 14 sept. 2021
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/login",
     *     "fr": "/connexion"
     * }, name="app_login",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        //dd('Entrée dans logout du SecurityController');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return new RedirectResponse($this->urlGenerator->generate('homepage'));
    }
}
