<?php

/**
 * This file is a part of the fablab portal package.
 * 
 * © Claude Migne <monkey3d@wanadoo.fr>
 * 
 * file : Twig/AppExtension.php - date : 19 sept. 2021
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
// https://twig.symfony.com/doc/3.x/advanced.html

namespace App\Twig;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use Laminas\Code\Generator\GeneratorInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $router;
    private $translator;
    
    public function __construct(
        UrlGeneratorInterface $router,
        TranslatorInterface $translator
        )
    {
        $this->router = $router;
        $this->translator = $translator;
    }
    
    public function getGlobals(): array
    {
        $button = [
            'add' => [
                'name' => $this->translator->trans('app.button.add', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'fas fa-plus-circle'
            ],
            'back_to_top' => [
                'name' => $this->translator->trans('app.button.back_to_top', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'bi bi-arrow-up-square'
            ],
            'close' => [
                'name' => $this->translator->trans('app.button.close', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-x-circle-fill'
            ],
            'deliver' => [
                'name' => $this->translator->trans('app.button.deliver', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'fas fa-truck'
            ],
            'download' => [
                'name' => $this->translator->trans('app.button.download', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-file-arrow-down-fill'
            ],
            'edit' => [
                'name' => $this->translator->trans('app.button.edit', [], 'app'),
                'class' => 'btn btn-warning btn-sm',
                'icon' => 'bi bi-pen-fill'
            ],
            'help' => [
                'name' => $this->translator->trans('app.button.help', [], 'app'),
                'class' => 'btn btn-link btn-outline-primary btn-sm',
                'icon' => 'bi bi-info-circle-fill'
            ],
            'list' => [
                'name' => $this->translator->trans('app.button.list', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'fas fa-list'
            ],
            'listen' => [
                'name' => $this->translator->trans('app.button.listen', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'fas fa-headphones'
            ],
            'order' => [
                'name' => $this->translator->trans('app.button.order', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'fas fa-shopping-cart'
            ],
            'search' => [
                'name' => $this->translator->trans('app.button.search', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'fas fa-search-plus'
            ],
            'send' => [
                'name' => $this->translator->trans('app.button.send', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'fas fa-comments'
            ],
            'sign_in' => [
                'name' => $this->translator->trans('app.button.sign_in', [], 'app'),
                'class' => 'btn btn-primary btn-sm',
                'icon' => 'bi bi-check-circle-fill'
            ],
            'submit' => [
                'name' => $this->translator->trans('app.button.submit', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'fas fa-check-circle'
            ],
            'upload' => [
                'name' => $this->translator->trans('app.button.upload', [], 'app'),
                'class' => 'btn btn-warning btn-sm',
                'icon' => 'fas fa-upload'
            ],
            'validate' => [
                'name' => $this->translator->trans('app.button.validate', [], 'app'),
                'class' => 'btn btn-success btn-sm',
                'icon' => 'bi bi-check-circle'
            ],
            'watch' => [
                'name' => $this->translator->trans('app.button.watch', [], 'app'),
                'class' => 'btn btn-info btn-sm',
                'icon' => 'fas fa-eye'
            ]
        ];
        return [
            'button_app' => $button,
        ];
    }
    //-------------------------------------------------------------------------------------------------------------
    public function getFunctions(): array
    {
        return [
            new TwigFunction('action', [$this, 'fragmentAction']),
        ];
    }
    //-------------------------------------------------------------------------------------------------------------
    /*
     * $type  - obligatoire : le type html du bouton : submit, reset, button
     * $label - obligatoire : le label du bouton : add, ..., help, .... Ce doit être un bouton initialisé dans getGlobals. 
     * $route - facultative : si la route figure génération d'une ancre <a href= - si vide génération d'un bouton <button 
     * $param - facultative : le tableau des paramètres
     * 
     * retourne l'élément html initialisé en utilisant les valeurs transmises en paramètre.
     *
     */
    public function fragmentAction(string $type, string $label, string $route, array $params): string
    {
        $htmlButtonType = ['submit', 'reset', 'button'];
        if (!in_array($type, $htmlButtonType)) {
            throw new \Exception("Le type de bouton transmis n'existe pas en html.");
        }
        $button = $this->getGlobals();
        if (!array_key_exists($label, $button['button_app'])) {
            throw new \Exception("Le label du bouton n'est pas défini.");
        }
        if (empty($route)) {
            $htmlElement = '<button type="'.$type.'" class="'.$button['button_app'][$label]['class'].'"';
            $attribute = '';
            foreach ($params as $key => $value) {
                $attribute = $attribute.$key.'="'.$value.'"';
            }
            $htmlElement = $htmlElement.$attribute.'><i class="'.$button['button_app'][$label]['icon'].'"></i> '.$button['button_app'][$label]['name'].'</button>';
        }
        else {
            try {
                $url = $this->router->generate($route, $params);
            } catch (RouteNotFoundException $e) {
                throw new \Exception("La route n'est pas définie.");
            }
            $htmlElement = '<a href="'.$url.'" type="'.$type.'" class="'.$button['button_app'][$label]['class'].'"><i class="'.$button['button_app'][$label]['icon'].'"></i> '.$button['button_app'][$label]['name'].'</a>';
        }
        return $htmlElement;
    }
}