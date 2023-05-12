<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : DocumentManagement/CategoryController.php - date : 16 oct. 2022
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\DocumentManagement;

use App\Entity\DocumentManagement\Category;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_', requirements: ["_locale" => "en|fr"])]
class CategoryController extends AbstractController
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
    
    #[Route('/list', name: 'list')]
    //-------------------------------------------------------------------------------------------------------------
    /**
     * Lister toutes les catégories avec pagination
     *
     */
    #[Route('/list/{page}', name: 'list')]
    public function list(int $page = 1): Response
    {
        $maxObjectsPerPage =  Category::MAX_ITEMS_PER_PAGE;
        $objects = $this->doctrine->getRepository(Category::class)->getObjects(($page * $maxObjectsPerPage) - $maxObjectsPerPage, $maxObjectsPerPage);
        $totalNumberObjects = count($objects);
        $lastPage = ceil($totalNumberObjects / $maxObjectsPerPage);
        return $this->render('document_management/category/list.html.twig', [
            'objects' => $objects,
            'last_page' => $lastPage,
            'current_page' => $page,
        ]);
    }
}