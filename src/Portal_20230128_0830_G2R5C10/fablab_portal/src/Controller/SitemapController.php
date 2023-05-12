<?php
/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : SitemapController.php - date : 14 fév. 2022
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class SitemapController extends AbstractController
{
    private $entityManager;
    private $router;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        RouterInterface $router
        )
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }
    /**
     * @Route({
     *      "en": "/sitemap.xml", 
     *      "fr": "/sitemap.xml"
     * }, name="sitemap",
     *      requirements={
     *          "_locale": "en|fr",
     *      },
     * defaults={"_format"="xml","_locale"="fr"})
     */
    public function index(Request $request): Response
    {
        $hostname = $request->getSchemeAndHttpHost(); // récupération du nom d'hôte depuis l'URL
        $routeToExclude = ['documentary', 'ldap_list', 'read', 'sitemap', 'user_profile'];
        $pattern01 = '/^app_/';
        $pattern02 = '/^admin_/';
        $urls = [];
        $routeCollection = $this->router->getRouteCollection();
        foreach ($routeCollection->all() as $key => $value)
        {
            $data = $value->getDefaults();
            //if ($value->getPath() == '/fr/équipe') dd($value); // 14/02/2022 - pour tests
            if (isset($data['_controller']) && isset($data['_locale']) && isset($data['_canonical_route']) && $data['_locale'] == $request->getLocale()) {
                if (!preg_match($pattern01, $data['_canonical_route']) && !preg_match($pattern02, $data['_canonical_route']) && !in_array($data['_canonical_route'], $routeToExclude)) {
                    $urls[] = [
                        'loc' => str_replace("{_locale}", "fr",$value->getPath()), // 18/02/2022 : appelé directement cette URL ne donne pas de locale (?)
                        'lastmod' => "2022-03-04",
                        //'changefreq' => 'weekly', // +18/02/2022 - -06/03/2022 Google ignore les valeurs <priority> et <changefreq>
                    ];
                }
            }
        }
        $response = new Response(
            $this->renderView('sitemap/index.html.twig',[
                'urls' => $urls,
                'hostname' => $hostname,
            ]), // compact('urls', 'hostname') notation plus courte quand les paires clefs valeurs sont identiques en nom
            200
        );
        // ajout entête http
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
