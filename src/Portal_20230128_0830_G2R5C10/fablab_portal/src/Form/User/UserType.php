<?php

namespace App\Form\User;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\UuidToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UuidType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options['ldap_data']->getAttribute('mail')[0]);
        $builder
            ->add('uid', UuidType::class, [
                'disabled' => true,
                'mapped' => true,
                'required' => true,
                //'data' => $options['ldap_data']->getAttribute('uid')[0],
                'attr' => ['disabled' => true] 
            ])
            
            ->add('cn', Hiddentype::class, [
                'mapped' => true,
                'required' => true,
                //'data' => $options['ldap_data']->getAttribute('cn')[0]
            ])
            
            ->add('sn', TextType::class, [
                'mapped' => true,
                'required' => true,
                //'data' => $options['ldap_data']->getAttribute('cn')[0]
            ])
            ->add('mail', EmailType::class, [
                'mapped' => true,
                'required' => true,
                //'data' => $options['ldap_data']->getAttribute('mail')[0]
            ])
            ->add('displayName', TextType::class, [
                'empty_data' => 'non renseigné',
                'mapped' => true,
                'required' => true,
                //'data' => $options['ldap_data']->getAttribute('displayName')[0]
            ])
            ->add('givenName', TextType::class, [
                //'empty_data' => 'non renseigné',
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'placeholder' => 'non renseigné'
                ]
                //'data' => $options['ldap_data']->getAttribute('givenName')[0]
            ])
            ->add('homePhone', TelType::class, [
                'mapped' => true,
                'required' => false,
                //'empty_data' => '+33 x xx xx xx',
                //'data' => isset($options['ldap_data']->getAttribute('homePhone')[0]) ? $options['ldap_data']->getAttribute('homePhone')[0] : '+33 x xx xx xx'
            ])
            ->add('mobile', TelType::class, [
                'mapped' => true,
                'required' => false,
                //'empty_data' => '+33 x xx xx xx',
                //'data' => isset($options['ldap_data']->getAttribute('mobile')[0]) ? $options['ldap_data']->getAttribute('mobile')[0] : '+33 x xx xx xx'
            ])
            ->add('homePostalAddress', TextType::class, [
                'mapped' => false,
                'required' => false,
                //'empty_data' => '+33 x xx xx xx',
                //'data' => isset($options['ldap_data']->getAttribute('homePostalAddress')[0]) ? $options['ldap_data']->getAttribute('homePostalAddress')[0] : 'Non renseignée'
            ])
            /*
            ->add('title', TextType::class, [
                'mapped' => false,
                'required' => false,
                //'empty_data' => '+33 x xx xx xx',
                'data' => isset($options['ldap_data']->getAttribute('title')[0]) ? $options['ldap_data']->getAttribute('title')[0] : 'Non renseignée'
            ])
            */
            ->add('title', ChoiceType::class, [
                'choices' => User::TITLES,
                /*
                'choice_attr' => [
                    'Monsieur' => ['data-color' => 'blue'],
                    'Madame' => ['data-color' => 'red'],
                ],
                */
                'mapped' => true,
                'required' => true,
                //'empty_data' => '+33 x xx xx xx',
                //'data' => isset($options['ldap_data']->getAttribute('title')[0]) ? $options['ldap_data']->getAttribute('title')[0] : 'Non renseignée'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
       // $resolver->setRequired(['ldap_data']);
    }
}
