<?php

/**
 * This file is a part of the SHU package.
 * SHU : Smart Home Ultimate
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : customFilters.php - date : 11 sept. 2022

 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\TwigExtension\Common;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class customFilters extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('perms', [$this, 'formatPerms']),
            new TwigFilter('json2array', [$this, 'jsonDecode']),
            new TwigFilter('of_type', [$this, 'ofType']),
            new TwigFilter('base64_encode', 'base64_encode'),
            new TwigFilter('base64_decode', 'base64_decode'),
            // https://stackoverflow.com/questions/11847751/how-to-convert-cast-object-to-array-without-class-name-prefix-in-php
            new TwigFilter('array', function (object $value) {
                //$name = get_class($value);
                $raw = (array) $value;
                $attributes = [];
                $matches = [];
                foreach ($raw as $k => $v) {
                    //$attributes[preg_replace('('.$name.'|\*|)', '', $attr)] = $val;
                    $k = preg_match('/^\x00(?:.*?)\x00(.+)/', $k, $matches) ? $matches[1] : $k;
                    $attributes[$k] = $v;
                }
                //dd($attributes);
                //return (array) $value;
                return $attributes;
            }),
        ];
    }

    public function formatPerms($number)
    {
        $perms="";
        for ($i=0; $i<3; $i++) {
            $car = substr($number, $i, 1);
            switch($car) {
                case '0':
                    $perms = $perms."---";
                    break;
                case '4':
                    $perms = $perms."r--";
                    break;
                case '5':
                    $perms = $perms."r-x";
                    break;
                case '7':
                    $perms = $perms."rwx";
                    break;
                default:
                    $perms = $perms.$car;
            }
        }
        return $perms;
    }

    public function jsonDecode(string $string): array
    {
        return json_decode($string, true) ;
    }

    public function ofType($var): string
    {
        $ret ="";
        if (is_array($var)) {
            $ret = 'array';
        }
        if (is_bool($var)) {
            $ret = 'boolean';
        }
        if (is_countable($var)) {
            $ret = 'countable';
        }
        if (is_int($var)) {
            $ret = 'integer';
        }
        if (is_string($var)) {
            $ret = 'string';
        }

        if ($ret == "") {
            return $var;
        } else {
            return $ret;
        }
    }
}
