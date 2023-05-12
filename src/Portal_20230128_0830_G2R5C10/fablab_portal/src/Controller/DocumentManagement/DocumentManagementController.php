<?php

/**
 * This file is a part of the fablab portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : DocumentManagement/DocumentManagementController.php - date : 16 oct. 2022
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace App\Controller\DocumentManagement;

use App\Entity\DocumentManagement\Author;
use App\Entity\DocumentManagement\Category;
use App\Entity\DocumentManagement\Document;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/document/management', name: 'document_management_', requirements: ["_locale" => "en|fr"])]
class DocumentManagementController extends AbstractController
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
    #[Route('/home/{page}', name: 'home')]
    public function home(int $page = 1): Response
    {
        $numberAuthors = $this->doctrine->getRepository(Author::class)
        ->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->getQuery()
        ->getSingleScalarResult();
        
        $numberCategories = $this->doctrine->getRepository(Category::class)
        ->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->getQuery()
        ->getSingleScalarResult();
        
        $numberDocuments = $this->doctrine->getRepository(Document::class)
        ->createQueryBuilder('a')
        ->select('COUNT(a)')
        ->getQuery()
        ->getSingleScalarResult();
        
        $maxObjectsPerPage =  6; // 12 : division bootstrap
        $objects = $this->doctrine->getRepository(Document::class)->getObjectsByDate(($page * $maxObjectsPerPage) - $maxObjectsPerPage, $maxObjectsPerPage);
        $totalNumberObjects = count($objects);
        $lastPage = ceil($totalNumberObjects / $maxObjectsPerPage);
        
        return $this->render('document_management/home.html.twig', [
            'numberAuthors' => $numberAuthors,
            'numberCategories' => $numberCategories,
            'numberDocuments' => $numberDocuments,
            'objects' => $objects,
            'last_page' => $lastPage,
            'current_page' => $page
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    #[Route('/pricing', name: 'pricing')]
    public function pricing(): Response
    {
        return $this->render('document_management/pricing.html.twig', []);
    }
}