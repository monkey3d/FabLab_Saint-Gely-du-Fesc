<?php

/**
 * This file is a part of the fablab portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : Blog/BlogController.php - date : 10 nov. 2022
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Blog;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog', name: 'blog_', requirements: ["_locale" => "en|fr"])]
class BlogController extends AbstractController
{
    private $doctrine;
    private $params;
    public function __construct(
        ManagerRegistry $doctrine,
        ParameterBagInterface $params
        ) {
            $this->doctrine = $doctrine;
            $this->params = $params;
    }
    //-------------------------------------------------------------------------------------------------------------
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            
        ]);
    }
}
