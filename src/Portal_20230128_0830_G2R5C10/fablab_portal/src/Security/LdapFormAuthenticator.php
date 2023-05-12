<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : Security/LdapFormAuthenticator.php - date : 14 sept. 2021
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator; // déprécié depuis 5.3

use Symfony\Contracts\Translation\TranslatorInterface;

class LdapFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $doctrine;
    private UrlGeneratorInterface $urlGenerator;
    private $csrfTokenManager;
    protected $ldap;
    private $translator;
    private $security;

    public function __construct(
        ManagerRegistry $doctrine,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        Ldap $ldap,
        TranslatorInterface $translator,
        Security $security
        )
    {
        $this->doctrine = $doctrine;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->ldap = $ldap;
        $this->translator = $translator;
        $this->security = $security;
    }
    
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
        && $request->isMethod('POST');
    }
    
    public function getCredentials(Request $request)
    {
//dump("App - LdapFormAuthenticator - getCredentials");
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
            );
        
        return $credentials;
    }
    
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
//dump("App - LdapFormAuthenticator - getUser");
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $userProvider->loadUserByIdentifier($credentials['username']);
        
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }
//dump("App - LdapFormAuthenticator - getUser - user");
//dump($user);
        return $user;
    }
    
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        try {
            $this->ldap->bind($user->getEntry()->getDn(), $credentials['password']);
        } catch (ConnectionException $e) {
            return false;
        }
        return true;
    }

    public function authenticate(Request $request): PassportInterface
    {
//dump("App - LdapFormAuthenticator - authenticate");
        $password = $request->request->get('password');
//dump($password);
        $username = $request->request->get('username');
//dump($username);
        $csrfToken = $request->request->get('_csrf_token');
//dump($csrfToken);

        $request->getSession()->set(Security::LAST_USERNAME, $username);
        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate',$csrfToken),
            ]
        );
    }


    //public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        $user = $this->security->getUser();
        //dd($user->getEntry()->getAttributes()['uid'][0]);
        $userBDD = $this->doctrine->getRepository(User::class)->findOneBy([
            'uid' => $user->getEntry()->getAttributes()['uid'][0]
        ]);
        //dd($userBDD);
        //$user->setIsActive(true);
        //$user->setIsConnected(true);
        //$user->setLastActivity(new \DateTime());
        $userBDD->setLoginCount($userBDD->getLoginCount()+1);
        $userBDD->setLastLogin(new \DateTime());
        $this->doctrine->getManager()->flush();

        $parameters = $request->request->all();
        //dd($user->getEntry()->getAttributes()['givenName'][0]);
        $request->getSession()->getFlashBag()->add(
            'info',
            $this->translator->trans('app.msg.successful_connection', ['username' => $user->getEntry()->getAttributes()['givenName'][0]], 'app')
        );
        
        //if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        //return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->urlGenerator->generate('user_profile', [
            'user' => $user
            ])); // <== Adapter la route selon les besoins
    }
/*    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
            
            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];
        
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
*/
    protected function getLoginUrl(): string
    {
//dump("App - LdapFormAuthenticator - getLoginUrl");
        //$locale = $request->getLocale();
        return $this->urlGenerator->generate(self::LOGIN_ROUTE); // self::LOGIN_ROUTE
        
    }
}
