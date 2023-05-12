<?php

/**
 * This file is a part of the fablab portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : CustomLdapUserProvider.php - date : 24 sept. 2021
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Security;

use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Security\LdapUser;
use Symfony\Component\Ldap\Security\LdapUserProvider;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\Exception\ExceptionInterface;
use Symfony\Component\Ldap\LdapInterface;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class CustomLdapUserProvider extends LdapUserProvider
{
    
    private $defaultRoles = ['ROLE_USER'];
    private $passwordAttribute;
    private $extraFields = [];
    
    /**
     * Loads a user from an LDAP entry
     * Cette méthode surcharge la méthode de base et alimente l'utilisateur Symfony avec les données extraites du Ldap.
     *
     * @param string $username
     * @param Entry $entry
     * @return UserInterface
     */
    public function loadUser(string $username, Entry $entry): UserInterface
    {
//dd("App - CustomLdapUserProvider - loadUser");
        $password = null;
        $extraFields = [];
        
        if (null !== $this->passwordAttribute) {
            $password = $this->getAttributeValue($entry, $this->passwordAttribute);
        }

        if ($this->extraFields) {
            foreach ($this->extraFields as $field) {
                $extraFields[$field] = $this->getAttributeValue($entry, $field);
            }
        }
//dd($entry);
        $results = [];
        /*
        if ($entry->getAttribute("memberOf")) {
            foreach ($entry->getAttribute("memberOf") as $LdapGroupDn)
            {
                $results[]= "ROLE_".ldap_explode_dn($LdapGroupDn,1)[0];
            }
        }
        */
        $results[]= "ROLE_ADMIN";
//dd($results);
        
        if ($entry->getAttribute("gidNumber")) {
            foreach ($entry->getAttribute("gidNumber") as $LdapGroupDn)
            {
                //dd($LdapGroupDn);
                //$results[]= "ROLE_".ldap_explode_dn($LdapGroupDn,1)[0];
                switch($LdapGroupDn) {
                    case 501:
                        $results[]= "ROLE_USER";
                        break;
                    case 502:
                        $results[]= "ROLE_SUPER_ADMIN";
                        break;
                    default:
                        // lever une exception
                }
            }
        }
//dd($results);
//dump("App - CustomLdapUserProvider - loadUser - this->defaultRoles");
//dump($this->defaultRoles);
        if (!empty($results))
            $roles=$results;
            else
                $roles=$this->defaultRoles;
                //$roles = ['ROLE_USER'];
//dump($roles);
        return new LdapUser($entry, $username, $password, $roles, $extraFields);
    }
    
    private function getAttributeValue(Entry $entry, string $attribute)
    {
//dump("getAttributeValue");
        if (!$entry->hasAttribute($attribute)) {
            throw new InvalidArgumentException(sprintf('Missing attribute "%s" for user "%s".', $attribute, $entry->getDn()));
        }
        
        $values = $entry->getAttribute($attribute);
        
        if (1 !== \count($values)) {
            throw new InvalidArgumentException(sprintf('Attribute "%s" has multiple values.', $attribute));
        }
        return $values[0];
    }
    
}
