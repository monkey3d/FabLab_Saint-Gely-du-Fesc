<?php

namespace App\Form\DocumentManagement;

use App\Entity\DocumentManagement\Author;
use App\Entity\DocumentManagement\Category;
use App\Entity\DocumentManagement\Document;

use Doctrine\ORM\EntityRepository;

use FOS\CKEditorBundle\Form\Type\CKEditorType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('summary', CKEditorType::class, [
                'config_name' => 'app_config'
            ])
            //->add('releaseDate')
            ->add('numberViews', null, ['disabled' => true])
            ->add('documentName')
            //->add('documentSize')
            //->add('documentMimeType')
            //->add('updatedAt')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('p')
                    ->addOrderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'attr' => ['rows' => '10', 'cols' => '25', 'style' => "width:400px;height:400px"]
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('p')
                    ->addOrderBy('p.firstName', 'ASC')
                    ->addOrderBy('p.name', 'ASC');
                },
                'choice_label' => 'firstNameName',
                'expanded' => false,
                'multiple' => true,
                'required' => false,
                'attr' => ['rows' => '10', 'cols' => '25', 'style' => "width:400px;height:400px"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
