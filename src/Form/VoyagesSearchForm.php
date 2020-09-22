<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoyagesSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source', ChoiceType::class, [
                'label' => 'Место отправления',
                'choices' => $options['stations']
            ])
            ->add('destination', ChoiceType::class, [
                'label' => 'Место назначения',
                'choices' => $options['stations']
            ])
            ->add('date', DateType::class, [
                'label' => 'Дата отправления',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'stations' => null
        ]);
    }
}