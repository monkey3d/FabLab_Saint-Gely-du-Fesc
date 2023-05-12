<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : DocumentManagement/DocumentManagementController.php - date : 7 jan. 2023
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\SiteManagement;

use App\Entity\SiteManagement\Setting;

use App\Form\SiteManagement\SettingType;

use Doctrine\Persistence\ManagerRegistry;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site/management', name: 'site_management_', requirements: ["_locale" => "en|fr"])]
#[IsGranted('ROLE_ADMIN')]
class SiteManagementController extends AbstractController
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
    
    #[Route('/home', name: 'home')]
    
    public function home(Request $request): Response
    {
        //$setting = $this->doctrine->getRepository(Setting::class)->findBy([], ['id' => 'desc'], 1);
        $id = 1;
        $setting = $this->doctrine->getRepository(Setting::class)->find($id);
        //dd($setting);
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //dd($data);
            $this->doctrine->getManager()->persist($setting);
            $this->doctrine->getManager()->flush();
            return $this->redirectToRoute('user_profile');
        }
        return $this->renderForm('site_management/home.html.twig', [
            'form' => $form
        ]);
    }
}
