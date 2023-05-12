<?php

/**
 * This file is a part of the SHU package.
 * SHU : Smart Home Ultimate
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : TypeCheckExtensions.php - date : 27 juin 2022

 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\TwigExtension\Common;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class TypeCheck
 */
class TypeCheck extends AbstractExtension
{
    public function __construct(ContainerBagInterface $params, string $projectDir)
    {
        $this->params = $params;
        $this->projectDir = $projectDir;
    }
    public function getFunctions()
    {
        return [
            'isDateTime' => new TwigFunction('isDateTime', function ($value) {
                return ($value instanceof \DateTime);
            }),
            'isFileExists' => new TwigFunction('isFileExists', function ($value) {
                return $this->projectDir.'/public/'.$this->params->get('vich.path.avatar')."/".$value;
                /*
                 * dev et de prod n'ont pas le même DOC_ROOT
                 */
                /*
                if ($_SERVER['APP_ENV'] === 'dev') {
                    //return file_exists($this->projectDir.'/public/'.$this->params->get('vich.path.avatar')."/".$value); // +24/08/2022
                    return $this->projectDir.'/public/'.$this->params->get('vich.path.avatar')."/".$value;
                }
                if ($_SERVER['APP_ENV'] === 'prod') {
                    //dd($this->projectDir.'/public/'.$this->params->get('vich.path.avatar')."/".$value);
                    return $this->projectDir.'/public/'.$this->params->get('vich.path.avatar')."/".$value;
                }
                */
            })
        ];
    }
}
