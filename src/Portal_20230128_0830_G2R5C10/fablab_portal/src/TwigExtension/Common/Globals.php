<?php

/**
 * This file is a part of the fablab_ portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : TwigExtension/Common/Globals.php - date : 26 mai 2022
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\TwigExtension\Common;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\GlobalsInterface;

class Globals extends AbstractExtension implements GlobalsInterface
{
    private $router;
    private $translator;

    public function __construct(
        UrlGeneratorInterface $router,
        TranslatorInterface $translator
    ) {
        $this->router = $router;
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('app_action', [$this, 'getAppAction']),
        ];
    }

    public function getGlobals(): array
    {
        $app_buttons = [
            'add' => [
                'name' => $this->translator->trans('app.button.add', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-plus-circle'
            ],
            'back_to_top' => [
                'name' => $this->translator->trans('app.button.back_to_top', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-arrow-up-square'
            ],
            'cancel' => [
                'name' => $this->translator->trans('app.button.cancel', [], 'app'),
                'class' => 'btn btn-light btn-sm',
                'icon' => 'bi bi-x-diamond-fill'
            ],
            'delete' => [
                'name' => $this->translator->trans('app.button.delete', [], 'app'),
                'class' => 'btn btn-danger btn-sm',
                'icon' => 'bi bi-trash',
                //'event' => 'onclick="return confirm(\'êtes-vous sûr de vouloir supprimer ?\')"'
                //'event' => 'onclick="submitConfirm()"'
            ],
            'deliver' => [
                'name' => $this->translator->trans('app.button.deliver', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-truck'
            ],
            'download' => [
                'name' => $this->translator->trans('app.button.download', [], 'app'),
                'class' => 'btn btn-warning btn-sm',
                'icon' => 'bi bi-download'
            ],
            'edit' => [
                'name' => $this->translator->trans('app.button.edit', [], 'app'),
                'class' => 'btn btn-warning btn-sm',
                'icon' => 'bi bi-pencil-square'
            ],
            'empty_trash' => [
                'name' => $this->translator->trans('app.button.empty_trash', [], 'app'),
                'class' => 'btn btn-danger btn-sm',
                'icon' => 'bi bi-trash'
            ],
            'graph' => [
                'name' => $this->translator->trans('app.button.graph', [], 'app'),
                'class' => 'btn btn-light btn-sm',
                'icon' => 'bi bi-graph-up-arrow'
            ],
            'help' => [
                'name' => $this->translator->trans('app.button.help', [], 'app'),
                'class' => 'btn btn-light btn-sm',
                'icon' => 'bi bi-info-circle'
            ],
            'list' => [
                'name' => $this->translator->trans('app.button.list', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-list'
            ],
            'listen' => [
                'name' => $this->translator->trans('app.button.listen', [], 'app'),
                'class' => 'btn btn-secondary btn-sm',
                'icon' => 'bi bi-headphones'
            ],
            'order' => [
                'name' => $this->translator->trans('app.button.order', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-command'
            ],
            'read' => [
                'name' => $this->translator->trans('app.button.read', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-eyeglasses'
            ],
            'register' => [
                'name' => $this->translator->trans('app.button.register', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-calendar-check-fill'
            ],
            'reply' => [
                'name' => $this->translator->trans('app.button.reply', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'bi bi-reply'
            ],
            'search' => [
                'name' => $this->translator->trans('app.button.search', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-search'
            ],
            'send' => [
                'name' => $this->translator->trans('app.button.send', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'bi bi-send'
            ],
            'sign_in' => [
                'name' => $this->translator->trans('app.button.sign_in', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'bi bi-box-arrow-in-right'
            ],
            'speech' => [
                'name' => $this->translator->trans('app.button.speech', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-mic-fill'
            ],
            'submit' => [
                'name' => $this->translator->trans('app.button.submit', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-check-circle'
            ],
            'switchoff' => [
                'name' => $this->translator->trans('app.button.switch.off', [], 'app'),
                'class' => 'btn btn-danger btn-sm',
                'icon' => 'bi bi-toggle2-off'
            ],
            'switchon' => [
                'name' => $this->translator->trans('app.button.switch.on', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-toggle2-on'
            ],
            'upload' => [
                'name' => $this->translator->trans('app.button.upload', [], 'app'),
                'class' => 'btn btn-warning btn-sm',
                'icon' => 'bi bi-upload'
            ],
            'validate' => [
                'name' => $this->translator->trans('app.button.validate', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-check-circle'
            ],
            'watch' => [
                'name' => $this->translator->trans('app.button.watch', [], 'app'),
                'class' => 'btn btn-secondary btn-sm',
                'icon' => 'bi bi-eye'
            ]
        ];
        return [
            'app_buttons' => $app_buttons,
        ];
    }
    //-------------------------------------------------------------------------------------------------------------
    /*
     * $type  : le type du bouton html : submit, reset, button - obligatoire
     * $label : le label du bouton : add, ... help, ... - obligatoire
     * $route : la route à générer dans l'ancre <a href= - facultatif : si vide alors ""
     * $param : le tableau des paramètres de la route - facultatif
     * $other : un string - facultatif
     *
     * Exemple d'appel dans une vue twig :
     *  - {{ app_action('submit', 'validate', "", {}) | raw }}
     *  - {{ app_action('button', 'listen', 'audio_listen', {'id': audio.id}) | raw }}
     *  - {{ app_action('button', 'cancel', "", {}, 'data-bs-dismiss="modal"') | raw }}
     *  - {{ app_action('button', 'speech', "", {}, 'onclick="runSpeechRecognition()"') | raw }}
     *
     */
    public function getAppAction(string $type, string $label, string $route, array $param, string $other = null): string
    {
        $htmlButtonType = ['submit', 'reset', 'button'];
        if (!in_array($type, $htmlButtonType)) {
            throw new \Exception("Le type de bouton transmis n'existe pas en html.");
        }
        $buttons = $this->getGlobals()['app_buttons'];

        if (!array_key_exists($label, $buttons)) {
            throw new \Exception("Le label du bouton n'est pas défini.");
        }
        if (empty($route)) {
            //$url = "#";
            $startElem = '<button ';
            $endElem = ' </button>';
        } else {
            try {
                $url = $this->router->generate($route, $param);
            } catch (RouteNotFoundException $e) {
                throw new \Exception("La route n'est pas définie.");
            }
            $startElem = '<a href="'.$url.'" ';
            $endElem = ' </a>';
        }
        if (!isset($buttons[$label]['event'])) {
            $buttons[$label]['event'] = '';
        }
        //$x = $x.'type="'.$type.'" class="'.$buttons[$label]['class'].'" '.$buttons[$label]['event'].'><i class="'.$buttons[$label]['icon'].'"></i> '.$buttons[$label]['name'].$y;
        $elem = $startElem
            .'type="'.$type
            .'" class="'.$buttons[$label]['class']
            .'" '.$buttons[$label]['event'].' '.$other
            .'><i class="'.$buttons[$label]['icon'].'"></i> '.$buttons[$label]['name']

            .$endElem;
        //dd($elem);
        return $elem;
    }
}
