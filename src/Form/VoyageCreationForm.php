<?php

namespace App\Form;

use App\Entity\Train;
use App\Entity\Voyage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyageCreationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Наименование поездки'
            ])
            ->add('departmentDate', DateType::class, [
                'label' => 'Дата отправления',
                'widget' => 'single_text',
            ])
            ->add('train', EntityType::class, [
                'label' => 'Маршрут',
                'class' => Train::class,
                'choice_label' => 'route'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Добавить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data-class', Voyage::class);
    }
}