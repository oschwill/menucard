<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Meals;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Kategorie'
            ])
            ->add('name', null, [
                'label' => 'Name',
            ])
            ->add('anhang', FileType::class, [
                'mapped' => false
            ])
            ->add('description', null, [
                'label' => 'Beschreibung',
            ])
            ->add('price', null, [
                'label' => 'Preis',
            ])
            ->add('speichern', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meals::class,
        ]);
    }
}
