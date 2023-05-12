<?php
/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : Listener/LogRoute.php - date : 14 sept. 2021
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace App\Listener;

use App\Entity\Log\Route;

use Doctrine\ORM\EntityManager;

//use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;

//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LogRoute
{
    protected $tokenStorage;
    protected $em;
    protected $securityContext;
    
    public function __construct(
        UsageTrackingTokenStorage $tokenStorage,
        EntityManager $manager
        )
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $manager;
    }
    
    public function log(ControllerEvent $event)
    {
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }
        $request = $event->getRequest();
        //dd($request);
        $_route  = $request->attributes->get('_route');
        //dd($request->server->get('HTTP_USER_AGENT'));
        
        // 16-02-2020 : réactualisation des routes à exclure
        $routeToExclude = [
            "_wdt",
        ];
        
        if (in_array($_route, $routeToExclude)) return;
        $ipAddress = $request->server->get('REMOTE_ADDR');
        $_controller = $request->attributes->get('_controller');
        $params = $request->attributes->get('_route_params');
        $browser = $request->server->get('HTTP_USER_AGENT');
        /*
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
            if ($user instanceof User) {
                $userName = (string) $user->getUsername();
                // 20-07-2018 & 09-11-2019 : L'utilisateur est donc actif et il est inscrit une date time de dernière activité.
                $user->setActive(true);
                $user->setLastActivity(new \DateTime());
                $this->em->persist($user);
            }
            else {
                $userName = "anonyme";
            }
            $route = new Route();
            $route->setUsername($userName);
            $route->setDate(new \DateTime());
            $route->setIpadress($_SERVER["REMOTE_ADDR"]);
            $route->setRoute($_route);
            $route->setController($_controller);
            $route->setParams($params);
            $route->setBrowser($_SERVER["HTTP_USER_AGENT"]);
            //dd($route);
            $this->em->persist($route);
            $this->em->flush();
        }
        */
       
        $route = new Route();
        //$route->setUsername("anonyme");
        $route->setDate(new \DateTime());
        $route->setIpAddress($ipAddress);
        $route->setRoute($_route);
        $route->setController($_controller);
        $route->setParams($params);
        $route->setBrowser($browser);
        $this->em->persist($route);
        $this->em->flush();
    }
    
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            // user has just logged in
        }
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // user has logged in using remember_me cookie
        }
        $user = $this->tokenStorage->getToken()->getUser();
        $userName = $user->getUsername();
        $route = new Route();
        //echo $userName;exit;
        $route->setUsername($userName);
        $route->setDate(new \DateTime());
        $route->setIpadress($_SERVER["REMOTE_ADDR"]);
        $route->setRoute($_route);
        $route->setController($_controller);
        $route->setParams($params);
        $route->setBrowser($_SERVER["HTTP_USER_AGENT"]);
        //var_dump($traceroute);exit;
        $this->em->persist($route);
        $this->em->flush();
    }
}
