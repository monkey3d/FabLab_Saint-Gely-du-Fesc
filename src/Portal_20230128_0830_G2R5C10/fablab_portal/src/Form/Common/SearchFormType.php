<?php

/**
 * This file is a part of the SHU package.
 * SHU : Smart Home Ultimate
 * © Claude Migne <monkey3d@wanadoo.fr>
 *
 * file : SearchFormType.php - date : 1 juil. 2022

 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Common;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'keyword',
            SearchType::class,
            [
            'label' => 'app.action.search',
            'translation_domain' => 'app',
            'attr' => [
                'autofocus' => 'autofocus',
            ]
        ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
