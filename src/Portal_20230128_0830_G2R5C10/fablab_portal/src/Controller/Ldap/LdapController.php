<?php

/**
 * This file is a part of the fablab portal package.
 *
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : LdapController.php - date : 5 oct. 2022
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Ldap;

use App\Entity\Ldap\Constants;
use App\Entity\User;

use App\Form\User\UserType;

use App\Services\Ldap\Utils;

use Doctrine\Persistence\ManagerRegistry;

use Psr\Log\LoggerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/ldap", name="ldap_")
 */
class LdapController extends AbstractController
{
    private $doctrine;
    private $ldap;
    private $ldapLogger;
    private $translator;
    
    public function __construct(
        ManagerRegistry $doctrine,
        Utils $ldap,
        LoggerInterface $ldapLogger,
        TranslatorInterface $translator
    )
    {
        $this->doctrine = $doctrine;
        $this->ldap = $ldap;
        $this->ldapLogger = $ldapLogger;
        $this->translator = $translator;
    }
    
    //public const FILTER_ENTRIES = ['cn', 'uid'];
    //-------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/members/list",
     *     "fr": "/membres/lister"
     * }, name="members_list",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function members_list(): Response
    {
        /*
        $host = $this->getParameter('env(APP_LDAP_HOST)');
        $encryption = $this->getParameter('env(APP_LDAP_ENCRYPTION)');
        $baseDn = $this->getParameter('env(APP_LDAP_BASEDN)');
        $searchDn = $this->getParameter('env(APP_LDAP_SEARCHDN)');
        $password = $this->getParameter('env(APP_LDAP_SEARCHPASSWORD)');
        
        $ldap = Ldap::create('ext_ldap', [
            'host' => $host, // IP or hostname of the LDAP server
            'encryption' => $encryption, // The encryption protocol: ssl, tls or none (default)
        ]);
        
        $ldap->bind($searchDn, $password);
        //$entryManager = $ldap->getEntryManager();
        //$entryManager->add($entry);
         */
        $this->ldapLogger->notice(__FUNCTION__." - ldap server connection - user : ".$this->getUser()->getUserIdentifier());
        $ldap = $this->ldap->Connect();
        //$query = $ldap->query('dc=my-domain,dc=com', '(objectclass=inetOrgPerson)'); // +04/07/2022 : retourne tous les attributs
        $this->ldapLogger->notice(__FUNCTION__." - query ldap to find members - user : ".$this->getUser()->getUserIdentifier());
        $query = $ldap->query('dc=my-domain,dc=com', '(objectclass=inetOrgPerson)', ['filter' => CONSTANTS::MEMBER_FILTER_0]); // +uniquement ceux dans Constants.php
        
        //$query = $ldap->query('dc=my-domain,dc=com', '(&(objectClass=groupOfNames)(member=cn=Claude Migne,ou=users,dc=my-domain,dc=com))'); // +07/07/2022 - OK !
        //$query = $ldap->query('dc=my-domain,dc=com', '(objectclass=inetOrgPerson)', ['filter' => 'uid']); // +20/12/2022 - retourne tous les uid des users
        
        //$uid = "4e87154b-5497-4158-afa2-2bd14305982c";
        //$query = $ldap->query('dc=my-domain,dc=com', '(&(objectclass=inetOrgPerson)(cn=Claude Migne))', ['filter' => CONSTANTS::MEMBER_FILTER_0]);
        //$query = $ldap->query('dc=my-domain,dc=com', '(&(objectclass=inetOrgPerson)(uid=4e87154b-5497-4158-afa2-2bd14305982c))', ['filter' => CONSTANTS::MEMBER_FILTER_0]);
        //$query = $ldap->query('dc=my-domain,dc=com', '(&(objectclass=inetOrgPerson)(uid='.$uid.'))', ['filter' => CONSTANTS::MEMBER_FILTER_0]);
        //dd($query);
        //$results = $query->execute()->toArray();
        $this->ldapLogger->notice(__FUNCTION__." - query execution - user : ".$this->getUser()->getUserIdentifier());
        $results = $query->execute();
        $this->ldapLogger->notice(__FUNCTION__." - results ".print_r($results, true));
        $users = [];
        foreach ($results as $result) {
            $this->ldapLogger->notice(__FUNCTION__." - result ".print_r($result, true));
            if (!isset($result->getAttribute('uid')[0])) { // ce cas ne devrait pas arriver sauf si création du user dans le ldap hors applicatif
                // Le uid pivot n'est pas trouvé donc :
                // 1.- Ajout du nouvel user en BDD avec création de l'uid pivot
                $this->ldapLogger->notice(__FUNCTION__." - the pivot identifier does not exist");
                $uuid = Uuid::v4();
                $user = new User();
                $user->setUid($uuid);
                $this->doctrine->getManager()->persist($user);
                $this->doctrine->getManager()->flush();
                $this->ldapLogger->notice(__FUNCTION__." - new user created with uid : ".$uuid->toRfc4122());
                // 2.- mise à jour du ldap avec l'attribut uid pivot
                $ldap = $this->ldap->Connect();
                $entryManager = $ldap->getEntryManager();
                $result->setAttribute('uid', [$uuid->toRfc4122()]);
                $entryManager->update($result);
                $this->ldapLogger->notice(__FUNCTION__." - update ldap with uid : ".$uuid->toRfc4122());
            }
            else {
                $this->ldapLogger->notice(__FUNCTION__." - the pivot identifier exist : ".$result->getAttribute('uid')[0]);
                $uid = Uuid::fromString($result->getAttribute('uid')[0]);
                $user = $this->doctrine->getRepository(User::class)->findOneBy(['uid' => $uid]);
            }
            //dd($user);
            if ($user->isVerified()) {
                foreach ($result->getAttributes() as $key => $value) {
                    //echo "$key - $value[0] - "."set".ucfirst($key)." - ".PHP_EOL;
                    switch($key) {
                        case 'lastLogin':
                        case 'loginCount':
                        case 'uid':
                        case 'verified':
                            break;
                        default:
                            $method = 'set'.ucfirst($key);
                            if (method_exists(User::class, $method)) {
                                $user->$method($value[0]);
                            }
                    }
                }
                $users[] = $user;
            }
            $this->ldapLogger->notice(__FUNCTION__." - user table ready");
        }
        return $this->render('ldap/member/list.html.twig', [
            'users' => $users,
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/member/delete/{pivotId}",
     *     "fr": "/membre/supprimer/{pivotId}"
     * }, name="member_delete",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function member_delete(Request $request, string $pivotId): Response
    {
        //dd($pivotId);
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['uid' => $pivotId]);
        if (!$user) {
            throw $this->createNotFoundException('No user found for pivotId '.$pivotId);
        }
        $user->setVerified(false);
        //dd($user);
        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();
        $typemessage='success';
        $message = $this->translator->trans('app.msg.confirm_delete',[], 'app');
        $request->getSession()->getFlashBag()->add($typemessage, $message);
        return $this->redirect($this->generateUrl('ldap_members_list'));
    }
    //-------------------------------------------------------------------------------------------------------------
    /**
     * @Route({
     *     "en": "/member/edit/{pivotId}",
     *     "fr": "/membre/editer/{pivotId}"
     * }, name="member_edit",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    #Route('/member/edit/{pivotId}', name: 'member_edit')]
    public function member_edit(Request $request, string $pivotId = null): Response
    {
        //dump($uid);
        $message='';
        if (isset($pivotId))
        {
            $uid = Uuid::fromString($pivotId);
            //dd($uuid);
            /*
            $user = new User();
            $user->setUuid($uuid);
            $this->doctrine->getManager()->persist($user);
            $this->doctrine->getManager()->flush();
            dd('fin');
            */
            $user = $this->doctrine->getRepository(User::class)->findOneBy(['uid' => $uid]);
            //dd($user);
            $ldap = $this->ldap->Connect();
            $query = $ldap->query('dc=my-domain,dc=com', '(&(objectclass=inetOrgPerson)(uid='.$uid.'))', ['filter' => CONSTANTS::MEMBER_FILTER_0]);
            //$query = $ldap->query('dc=my-domain,dc=com', '(objectclass=inetOrgPerson)', ['filter' => CONSTANTS::MEMBER_FILTER_0]);
            $result = $query->execute();
            $entry = $result[0];
            //dd($entry);
            //$results = $result->toArray();
            //dump($results[0]->getAttributes());
            foreach ($entry->getAttributes() as $key => $value) {
                //echo "$key - $value[0] - "."set".ucfirst($key)." - ".PHP_EOL;
                if ($key !== 'uid') {
                    $method = 'set'.ucfirst($key);
                    if (method_exists(User::class, $method)) {
                        $user->$method($value[0]);
                    }
                }
            }
            //dd($results[0]->getAttribute('cn')[0]);
            //dd($results[0]);
            //$user->setCn($results[0]->getAttribute('cn')[0]);
            //dd($user);
            $title = $this->translator->trans('user.title.edit_member', [], 'user');
        }
        else {
            $uuid = Uuid::v4();
            $user = new User();
            $user->setUid($uuid);
            $this->doctrine->getManager()->persist($user);
            $this->doctrine->getManager()->flush();
            $this->ldapLogger->notice(__FUNCTION__." - new user created with uid : ".$uuid->toRfc4122()." by ".$this->getUser()->getUserIdentifier());
            
            $title = $this->translator->trans('user.title.get_member', [], 'user');;
        }
        //$form = $this->createForm(UserType::class, $user, ['ldap_data' => $results[0]]); // +23/12/2022 : transmission en options des infos du ldap
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData(); // + 23/12/2022 - récupération des champs mappés
            //dd($data);
            //$data = $form->get("mail")->getData(); // +23/12/2022 - récupération d'un champs non mappé - 18/01/2023 : ne doit plus exister
            $ldapProperties = [CONSTANTS::MEMBER_FILTER_0][0];
            $ldap = $this->ldap->Connect();
            $entryManager = $ldap->getEntryManager();
            
            if (isset($pivotId)) { // donc c'est une modif à faire dans ldap
                foreach ($ldapProperties as $property) {
                    $$property = $form->get($property)->getData();
                    if ( isset($$property)) {
                        //echo "$property - ${$property}"."<br>";
                        $entry->setAttribute($property, [$$property]);
                    }
                }
                $entryManager->update($entry);
            }
            else { // donc c'est une nouvelle entrée
                $entry = new Entry('cn='.$form->get('givenName')->getData().' '.$form->get('sn')->getData().',ou=People,dc=my-domain,dc=com', [
                    'sn'=> [$form->get('sn')->getData()],
                    'objectClass' => ['inetOrgPerson'],
                ]);
                foreach ($ldapProperties as $property) {
                    $$property = $form->get($property)->getData();
                    if ( isset($$property)) {
                        //echo "$property - ${$property}"."<br>";
                        $entry->setAttribute($property, [$$property]);
                    }
                }
                $entryManager->add($entry);
                // ajout dans le group user par défaut
                $query = $ldap->query('cn=user,ou=Groups,dc=my-domain,dc=com', '(objectclass=groupOfUniqueNames)');
                $result = $query->execute();
                $entry = $result[0];
                //dd($entry);
                $entryManager->addAttributeValues($entry, 'uniqueMember', ['cn='.$form->get('givenName')->getData().' '.$form->get('sn')->getData().',ou=People,dc=my-domain,dc=com']);
            }
            return $this->redirectToRoute('ldap_members_list');
        }
        return $this->renderForm('user/edit.html.twig', [
            'form' => $form,
            'title' => $title,
        ]);
    }
    //-------------------------------------------------------------------------------------------------------------
}
