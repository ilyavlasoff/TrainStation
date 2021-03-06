<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCreationForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('wagonType', ChoiceType::class, [
                'label' => 'Тип вагона',
                'choices' => $options['wagonTypes']
            ])
            ->add('sourceStation', ChoiceType::class, [
                'label' => 'Станция посадки',
                'choices' => $options['stationsOptions']
            ])
            ->add('destinationStation', ChoiceType::class, [
                'label' => 'Станция назначения',
                'choices' => $options['stationsOptions']
            ])
            ->add('wagonNumber', ChoiceType::class, [
                'label' => 'Номер вагона'
            ])
            ->add('bonusesToCheckout', TextType::class, [
                'label' => 'Списать бонусы'
            ])
            ->add('checkoutMethod', ChoiceType::class, [
                'label' => 'Тип оплаты',
                'choices' => $options['paymentTypes']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Создать заказ'
            ]);
        $builder->get('wagonNumber')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'wagonTypes' => null,
            'paymentTypes' => null,
            'csrf_protection' => false,
            'stationsOptions' => null,
            'wagonNumber' => null
        ]);
        $resolver->setDefaults([
            'validation_groups' => false,
        ]);
    }
}