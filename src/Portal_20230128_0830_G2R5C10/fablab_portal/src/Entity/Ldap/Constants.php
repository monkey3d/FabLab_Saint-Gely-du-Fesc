<?php

namespace App\Entity\Ldap;

class Constants
{
    // Les données récupérées du ldap
    public const MEMBER_FILTER_0 = [
        'cn', // Prénom Nom - ne peut être changé car clef du ldap
        'displayName',
        'givenName',
        'homePhone',
        'mail',
        'mobile',
        'sn',
        'title',
        'uid', // identifiant pivot entre le ldap et la base mariadb
        //'localityName',
        //'homePostalAddress',
        //'postalCode',
        //'street', 
        //'jpegPhoto',
        //'telephoneNumber'
    ];
    
}
