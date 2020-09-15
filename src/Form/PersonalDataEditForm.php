<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PersonalDataEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Значение e-mail не должно быть пустым'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/',
                        'message' => 'Значение имени некорректно'
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Имя',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Значение имени не должно быть пустым'
                    ])
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Фамилия',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Значение фамилии не должно быть пустым'
                    ])
                ]
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'Отчество'
            ])
            ->add('passportData', TextType::class, [
                'label' => 'Паспортные данные',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Значение e-mail не должно быть пустым'
                    ]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Паспортные данные некорректны'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data-class', User::class);
    }
}