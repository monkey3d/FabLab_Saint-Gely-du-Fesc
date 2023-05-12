<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : TrainingManagement/TrainingManagementController.php - date : 21 jan. 2023
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\TrainingManagement;

use App\Entity\TrainingManagement\Training;

use App\Services\Ldap\Utils;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/training/management', name: 'training_management_', requirements: ["_locale" => "en|fr"])]
class TrainingManagementController extends AbstractController
{
    private $doctrine;
    private $ldap;
    private $params;
    public function __construct(
        ManagerRegistry $doctrine,
        Utils $ldap,
        ParameterBagInterface $params
        ) {
            $this->doctrine = $doctrine;
            $this->ldap = $ldap;
            $this->params = $params;
    }
    //-------------------------------------------------------------------------------------------------------------
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        // là il faut lister les formations ayant des dates postérieures à la date - heure de l'interrogation
        //$training = $this->doctrine->getRepository(Training::class)->findAll();
        $training = $this->doctrine->getRepository(Training::class)->upcomingTraining();
        // ne pas présenter les formations où l'utilisateur est déjà inscrit
        return $this->render('training_management/home.html.twig', [
            'training' => $training,
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /*
     * Enregistrement d'un user sur une formation suivant les règles de gestion
     * id est l'identifiant de la formation
     */
    #[Route('/register/{id}', name: 'register')]
    public function register(int $id): Response
    {
        //dd($id);
        $train = $this->doctrine->getRepository(Training::class)->find($id);
        //récupération de l'utilisateur
        
        // le user n'est-il pas déjà inscrit pour cette formation ? - redondant avec lister ?
        
        // l'adhésion est-elle valide ?
        
        // relier la formation au user
        
        // moins 1 sur le nombre de place de la formation
        $train->setNumberAvailable($train->getNumberAvailable()-1);
        $this->doctrine->getManager()->flush();
        
        return $this->render('training_management/register.html.twig', [
            //'user' => $user,
            'train' => $train
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /*
     * Liste des users inscrits à une formation
     * id est l'identifiant de la formation
     * Retourne la formation et les users inscrits à la formation
     */
    #[Route('/list/registered/{id}', name: 'list_registered')]
    public function listRegistered(int $id): Response
    {
        $train = $this->doctrine->getRepository(Training::class)->find($id);
        $trainingUsers = $train->getTrainingUsers();
        $users = [];
        foreach ($trainingUsers as $key => $value) {
            $uuid = $value->getUser()->getUid();
            $uid = $uuid->toRfc4122();
            $userLdap = $this->ldap->getEntry($uid);
            $users[$key]['registrationDate'] = $value->getRegistrationDate();
            $users[$key]['ldap'] = $userLdap;
            
        }
        //dd($users);
        return $this->render('training_management/list/registered.html.twig', [
            'users' => $users,
            'train' => $train
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
}
