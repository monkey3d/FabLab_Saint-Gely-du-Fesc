<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : DocumentManagement/DocumentController.php - date : 13 oct. 2022
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\DocumentManagement;

//use App\Entity\DocumentManagement\Author;
//use App\Entity\DocumentManagement\Category;
use App\Entity\DocumentManagement\Document;

use App\Form\Common\SearchFormType;
use App\Form\DocumentManagement\DocumentType;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/document', name: 'document_', requirements: ["_locale" => "en|fr"])]
class DocumentController extends AbstractController
{
    private $doctrine;
    private $params;
    private $translator;
    public function __construct(
        ManagerRegistry $doctrine,
        ParameterBagInterface $params,
        TranslatorInterface $translator
        ) {
            $this->doctrine = $doctrine;
            $this->params = $params;
            $this->translator = $translator;
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     *
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, int $id = null): Response
    {
        $message='';
        if (isset($id))
        {
            $document = $this->doctrine->getRepository(Document::class)->find($id);
            if (!$document) {
                $message = $this->translator->trans('app.msg.no_object_found', [], 'app');
                throw $this->createNotFoundException(
                    $message.$id
                    );
            }
        }
        else
        {
            $document = new Document();
            //$document->setAuthor($this->getUser());
        }
        $form = $this->container->get('form.factory')->create(DocumentType::class, $document);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            dd($data);
        }
        
        return $this->render('document_management/document/edit.html.twig', [
            'form' => $form->createView(),
            'message' => $message
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     * Lister tous les documents avec pagination
     *
     */
    #[Route('/list/{page}', name: 'list')]
    public function list(int $page = 1): Response
    {
        $maxObjectsPerPage =  Document::MAX_ITEMS_PER_PAGE;
        $objects = $this->doctrine->getRepository(Document::class)->getObjectsByDate(($page * $maxObjectsPerPage) - $maxObjectsPerPage, $maxObjectsPerPage);
        $totalNumberObjects = count($objects);
        $lastPage = ceil($totalNumberObjects / $maxObjectsPerPage);
        return $this->render('document_management/document/list.html.twig', [
            'objects' => $objects,
            'last_page' => $lastPage,
            'current_page' => $page,
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     * 
     *
     * @Route("/read/{documentName}", name="read")
     */
    #[Route('/read/{documentName}', name: 'read')]
    public function read(string $documentName): Response
    {
        $document = $this->doctrine->getRepository(Document::class)->findOneBy(['documentName' => $documentName]);
        $document->setNumberViews($document->getNumberViews() + 1);
        $this->doctrine->getManager()->persist($document);
        $this->doctrine->getManager()->flush();
        $path = $this->params->get('kernel.project_dir').'/public/doc/';
        $response= new Response();
        $response->setContent(file_get_contents($path.$documentName));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=' . $documentName);
        return $response;
    }
    //------------------------------------------------------------------------------------------------------------
    #[Route('/search', name: 'search')]
    public function search(Request $request)
    {
        $form = $this->createForm(SearchFormType::class);
        return $this->render('document_management/document/search.html.twig', [
            'form' => $form->createView()
        ]);
    }
    //------------------------------------------------------------------------------------------------------------
    #[Route('/search/ajax', name: 'search_ajax')]
    public function searchAjax(Request $request)
    {
        $keyword = $request->get('keyword');
        if ($keyword) {
            $objects = $this->doctrine->getRepository(Document::class)->search($keyword);
        }
        return $this->render('document_management/document/_list.html.twig', ['objects' => $objects]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     *
     *
     * @Route("/watch/{documentName}", name="watch")
     */
    #[Route('/watch/{documentName}', name: 'watch')]
    public function watch(string $documentName): Response
    {
        $document = $this->doctrine->getRepository(Document::class)->findOneBy(['documentName' => $documentName]);
        $document->setNumberViews($document->getNumberViews() + 1);
        $this->doctrine->getManager()->persist($document);
        $this->doctrine->getManager()->flush();
        //$path = $this->params->get('kernel.project_dir').'/public/movie/';
        return $this->render('document_management/document/watch.html.twig', [
            'document' => '/public/movie/'.$documentName
        ]);
    }
}
