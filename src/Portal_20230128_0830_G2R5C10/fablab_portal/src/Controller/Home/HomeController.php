<?php

/**
 * This file is a part of the fablab portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : Home/HomeController.php - date : 14 sept. 2021
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;

class HomeController extends AbstractController
{
    private $params;

    public function __construct(
        ParameterBagInterface $params
        )
    {
        $this->params = $params;
    }
    //---------------------------------------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/homepage",
     *     "fr": "/accueil"
     * }, name="homepage",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function homepage(Request $request): Response
    {
        $host = $this->getParameter('env(APP_LDAP_HOST)');
        $encryption = $this->getParameter('env(APP_LDAP_ENCRYPTION)');
        $baseDn = $this->getParameter('env(APP_LDAP_BASEDN)');
        $searchDn = $this->getParameter('env(APP_LDAP_SEARCHDN)');
        $password = $this->getParameter('env(APP_LDAP_SEARCHPASSWORD)');
        
/*        
 * 
        $ldap = Ldap::create('ext_ldap', [
            'host' => $host, // IP or hostname of the LDAP server
            'encryption' => $encryption, // The encryption protocol: ssl, tls or none (default)
        ]);
        
        $ldap->bind($searchDn, $password);
*/
        
/*
        $entry = new Entry('cn=Claude Migne,dc=my-domain,dc=com', [
            'sn' => ['cuum5490'],
            'telephoneNumber' => ['0680315826'],
            'objectClass' => ['person'],
        ]);
*/
        
/*
        $entry = new Entry('cn=Claude Migne,dc=my-domain,dc=com', [
            'sn' => ['cuum5490'],
            'l' => ['Saint-Gély du Fesc'],
            'street' => ['239 rue du carigan'],
            'objectClass' => ['residentialPerson'],
        ]);
*/
        ////$entryManager = $ldap->getEntryManager();
        //$entryManager->add($entry);
/*
        $query = $ldap->query('dc=my-domain,dc=com', '(objectclass=inetOrgPerson)');
        dump($query);
        $results = $query->execute();
        $entry = $results[0];
        //dd($entry);
        //$phoneNumber = $entry->getAttribute('telephoneNumber');
        //dd($phoneNumber);
        
        //$entry->setAttribute('email', ['claude.migne@wanadoo.fr']);
        //$entryManager->update($entry);
*/        
        return $this->render('home/homepage.html.twig', [
            //'controller_name' => 'HomeController',
        ]);
    }
    //---------------------------------------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/get-member",
     *     "fr": "/devenir-membre"
     * }, name="get-member",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function getMember(Request $request): Response
    {
        return $this->render('home/get_member.html.twig', [
            
        ]);
    }
    //---------------------------------------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/team",
     *     "fr": "/équipe"
     * }, name="team",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function team(Request $request): Response
    {
        return $this->render('home/team.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/equipment",
     *     "fr": "/équipement"
     * }, name="equipment",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function equipment(Request $request): Response
    {
        return $this->render('home/equipment.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/what-is-a-fablab",
     *     "fr": "/cest-quoi-un-fablab"
     * }, name="what_fablab",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function what_fablab(Request $request): Response
    {
        return $this->render('home/what_fablab.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/project-progress",
     *     "fr": "/avancement-projet"
     * }, name="project_progress",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function project_progress(Request $request): Response
    {
        return $this->render('home/project_progress.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/participate-adventure",
     *     "fr": "/participer-aventure"
     * }, name="participate_adventure",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function participate_adventure(Request $request): Response
    {
        return $this->render('home/participate_adventure.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/news",
     *     "fr": "/actualités"
     * }, name="news",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function news(Request $request): Response
    {
        return $this->render('home/news.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/about",
     *     "fr": "/à-propos"
     * }, name="about",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function about(Request $request): Response
    {
        return $this->render('home/about.html.twig', [
            
        ]);
    }
    /**
     * @Route({
     *     "en": "/documentary/{type}",
     *     "fr": "/documentation/{type}"
     * }, name="documentary",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function documentary(Request $request, string $type): Response
    {
        //dd($request->get('type'));
        $type = $request->get('type');
        switch($type) {
            case 1:
                $documentary = ['title' => "Documentation officielle"];
                $meetings = [
                    [
                        "title" => "Les documents de référence de l'association",
                        "documents" => [
                            ["title" => 'Les statuts', "href" => "20191115_Statut.pdf", "author" => 'Claude Alibert & Claude Migne'],
                            ["title" => "La déclaration de l'association", "href" => "JO.pdf", "author" => 'Journal Officiel'],
                            ["title" => "Procès Verbal Composition du bureau", "href" => "FabLab SG PV CA Composition du bureau.pdf", "author" => 'Claude Alibert & Claude Migne'],
                            ["title" => 'Le rapport moral 2021', "href" => "FabLab SG PV Rapport moral et AG.pdf", "author" => 'Claude Alibert & Claude Migne'],
                            ["title" => "Le réglement intérieur (à venir)", "href" => "", "author" => ''],
                            ["title" => "Le logo du FabLab", "href" => "FabLab_SG03.pdf", "author" => "Claude Migne"],
                            ["title" => "Le slogan du FabLab", "href" => "APE.pdf", "author" => "Claude Alibert"],
                            ["title" => "Le QR code du site du FabLab", "href" => "QRcode-fablabsaintgely.pdf", "author" => "Claude Migne"],
                            ["title" => "Contrat d'engagement républicain", "href" => "Contrat_Engagement_Républicain.pdf", "author" => "Claude Alibert"],
                        ],
                    ],
                    [
                        "title" => "Les appels à projets",
                        "documents" => [
                            ["title" => 'La vidéo pour Leader - T1/2022', "href" => "VideoLeaderS2F3.mp4", "author" => 'Claude Alibert & Claude Migne'],
                        ]
                    ],
                ];
                break;
            case 2:
                $documentary = ["title" => "Soirée & formation & conférence & atelier"];
                $meetings = [
                    //"title" => "Les réunions : formation & conférence & atelier",
                    [
                        "title" => 'Réunion du 02/12/2021',
                        "documents" => [
                            [ "title" => "Constitution du Conseil d'Administration", "href" => "", "author" => ''],
                        ],
                    ],
                    [
                        "title" => 'Soirée du 13/01/2022',
                        "documents" => [
                            ["title" => 'Compte-rendu', "href" => "20220113_CR.pdf", "author" => 'Claude Migne'],
                            ["title" => 'Le protocole MQTT', "href" => "20220113_MQTT.pdf", "author" => 'Claude Migne'],
                        ],
                    ],
                    [
                        "title" => 'Soirée du 03/02/2022',
                        "documents" => [
                            [ "title" => 'Informations générales', "href" => "20220203 - Infos Générales.pdf", "author" => 'Claude Migne'],
                            [ "title" => 'Présentation du projet Home-Cockpit', "href" => "Sim_Pit_A10C_Project.pdf", "author" => 'Marc Cabès'],
                            [ "title" => 'Introduction aux CNC pilotées par Gcodes', "href" => "Fabab Saint Gély Machines et G-Codes.pdf", "author" => ' Claude Alibert'],
                        ],
                    ],
                    [
                        "title" => 'Soirée du 03/03/2022',
                        "documents" => [
                            [ "title" => 'Informations générales', "href" => "20220303-Infos générales.pdf", "author" => 'Claude Alibert'],
                            [ "title" => "Présentation du module M5stickC Plus basé sur l'ESP32", "href" => "20220303- M5stick5 Fançois 5.pdf", "author" => 'François Fabre'],
                            [ "title" => 'Ma réalisation de smart home "the big picture"', "href" => "20220303 - Projet Smarthome.pdf", "author" => 'Claude Migne'],
                        ],
                    ],[
                        "title" => 'Soirée du 07/04/2022',
                        "documents" => [
                            [ "title" => "Introduction aux lasers", "href" => "FabLab Intro lasers.key.pdf", "author" => 'Claude Alibert'],
                            [ "title" => "Flash rubrique Smart Home", "href" => "Rubrique_Smart_Home.pdf", "author" => 'Claude Migne'],
                            [ "title" => "RFID : un projet d'usage au FabLab", "href" => "GesMatLab.pdf", "author" => 'Claude Migne'],
                        ],
                    ],[
                        "title" => 'Soirée du 05/05/2022',
                        "documents" => [
                            [ "title" => "Dernières nouvelles", "href" => "Réunion du 5 mai 2022.pdf", "author" => 'Claude Alibert'],
                            [ "title" => "Usage & Evolution du Système d'Information", "href" => "20220505 - SI FabLab.pdf", "author" => 'Claude Migne'],
                            [ "title" => "Modélisation 3D (prochainement)", "href" => "", "author" => 'Olivier Sarrailh'],
                        ],
                    ],[
                        "title" => 'Soirée du 07/07/2022',
                        "documents" => [
                            [ "title" => "Dernières nouvelles", "href" => "Reunion  du 7 juillet 2022 2.pdf", "author" => 'Claude Alibert'],
                            [ "title" => "Principes et applications des LEDs adressables Multi-couleurs", "href" => "ws2812b-v7.pdf", "author" => 'François Fabre & Maxime Bigot'],
                        ],
                    ],[
                        "title" => 'Soirée du 15/09/2022',
                        "documents" => [
                            [ "title" => "Dernières nouvelles", "href" => "20220915-Infos Générales.pdf", "author" => 'Claude Alibert'],
                            [ "title" => "Méthodologie pour la découverte de l’électronique (prochainement)", "href" => "", "author" => 'Rémi Sarrailh'],
                        ],
                    ],[
                        "title" => 'Soirée du 06/10/2022',
                        "documents" => [
                            [ "title" => "Point projet", "href" => "20221006_Infos_Générales_S0F3.pdf", "author" => 'Claude Migne'],
                            [ "title" => "Panneaux solaires pour particuliers", "href" => "Introduction au photovoltaïque.pdf", "author" => 'Claude Alibert'],
                        ],
                    ]
                ];
                break;
            default:
                $documentary = [];
        }
        return $this->render('home/documentary.html.twig', [
            'documentary' => $documentary,
            'meetings' => $meetings
            
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/read/{document}",
     *     "fr": "/lire/{document}"
     * }, name="read",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function read($document)
    {
        //dd($document);
        //dd($this->params->get('kernel.project_dir'));
        $chemin = $this->params->get('kernel.project_dir').'/public/doc/';
        //dd($chemin.$document);
        $response= new Response();
        $response->setContent(file_get_contents($chemin.$document));
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-disposition', 'filename=' . $document);
        
        return $response;
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/watch/{document}",
     *     "fr": "/voir/{document}"
     * }, name="watch",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function watch($document)
    {
        //dd($document);
        //dd($this->params->get('kernel.project_dir'));
        $path = $this->params->get('kernel.project_dir').'/public/doc/';
        //dd($path);
        return $this->render('home/watch.html.twig', [
                'path' => $path,
                'document' => $document
            ]
        );
    }
}
