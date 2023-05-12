<?php

/**
 * This file is a part of the SHU package.
 * SHU : Smart Home Ultimate
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : Services/Ldap/Utils.php - date : 7 juil. 2022
 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Ldap;

use App\Entity\Ldap\Constants;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;

class Utils {
    private $params;
    
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    //-------------------------------------------------------------------------------------------------------------
    public function Connect()
    {
        $host = $this->params->get('env(APP_LDAP_HOST)');
        $encryption = $this->params->get('env(APP_LDAP_ENCRYPTION)');
        $baseDn = $this->params->get('env(APP_LDAP_BASEDN)');
        $searchDn = $this->params->get('env(APP_LDAP_SEARCHDN)');
        $password = $this->params->get('env(APP_LDAP_SEARCHPASSWORD)');
        
        $ldap = Ldap::create('ext_ldap', [
            'host' => $host, // IP or hostname of the LDAP server
            'encryption' => $encryption, // The encryption protocol: ssl, tls or none (default)
        ]);
        
        $ldap->bind($searchDn, $password);
        return $ldap;
    }
    //-------------------------------------------------------------------------------------------------------------
    public function getEntry(string $uid): ?Entry
    {
        $ldap = $this->Connect();
        $query = $ldap->query('dc=my-domain,dc=com', '(&(objectclass=inetOrgPerson)(uid='.$uid.'))', ['filter' => CONSTANTS::MEMBER_FILTER_0]);
        $result = $query->execute();
        $entry = $result[0];
        return $entry;
    }
}
