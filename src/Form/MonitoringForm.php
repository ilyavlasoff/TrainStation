<?php

namespace App\Form;

use App\Entity\Monitoring;
use App\Entity\Train;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonitoringForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', TextType::class, [
                'label' => 'местоположение'
            ])
            ->add('time', DateTimeType::class, [
                'label' => 'Время прохождения',
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ]
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
        $resolver->setDefault('data-class', Monitoring::class);
    }
}
