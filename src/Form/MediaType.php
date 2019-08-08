<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Actors;
use App\Entity\Genres;
use App\Repository\ActorsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('synopsis')
            ->add('released_year')
            ->add('poster')
            ->add('trailer')
            ->add('genres', EntityType::class, [
                'class' => Genres::class,
                'choice_label' => 'name'
            ])
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Choix du type',
                'choices' => [
                    'Série' => 'Série',
                    'Film' => 'Film'
                ]  
            ])
            ->add('duration', IntegerType::class)
            ->add('actors',EntityType::class, [
                'class'         => Actors::class,
                'choice_label'  => 'name',
                'label'         => 'Acteurs',
                'multiple'      => true,
                'mapped'        => false,
                'query_builder' => function(ActorsRepository $rep) {
                    return $rep->createQueryBuilder('a')
                                    ->distinct();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
