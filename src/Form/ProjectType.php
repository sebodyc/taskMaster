<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'titre du projet',
                'attr' => ['placeholder' => 'titre du projet']
            ])
            ->add('shortDescription', TextType::class, [
                'label' => 'description rapide',
                'attr' => ['placeholder' => 'description rapide du projet']
            ])
            ->add('deadline', DateType::class)
            ->add('description', TextareaType::class, [
                'label' => 'description ',
                'attr' => ['placeholder' => 'description exhaustive du projet']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
