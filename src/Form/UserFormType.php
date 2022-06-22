<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Position;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', EmailType::class, ['label' => false, 'attr' => ['placeholder' => 'Email']])
            ->add('plainPassword', RepeatedType::class, ['type' => PasswordType::class, 'invalid_message' => 'The password field value is not valid !!!',
                'first_options' => ['label' => false, 'attr' => ['placeholder' => 'Hasło']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Powtórz hasło']]])
            ->add('firstName', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'First name']])
            ->add('lastName', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Last name']])
            ->add('department', EntityType::class, ['label' => false, 'class' => Department::class, 'placeholder' => 'Select department', 'required' => true])
            ->add('position', EntityType::class, ['label' => false, 'class' => Position::class, 'placeholder' => 'Select position', 'required' => true, 'multiple' => false, 'expanded' => false])
            ->add('telephone', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Telephone']])
            ->add('street', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Street']])
            ->add('postalCode', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Postal code']])
            ->add('town', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Town']])
            ->add('note', TextareaType::class, ['label' => false, 'attr' => ['row' => '20', 'style' => 'min-height:150px'],'required'=>false])
            ->add('payRates', CollectionType::class, ['entry_type' => PayRateFormType::class,
                'entry_options' => ['label' => false], 'by_reference' => false, 'required'=>true, 'allow_add' => true, 'allow_delete' => true, 'prototype' => true])
            ->add('roles', ChoiceType::class, ['label'=>false,'placeholder'=>'Wybierz uprawnienia',
                'choices'  => [
                    'Administrator'=>'ROLE_ADMIN','Kierownik'=>'ROLE_KIEROWNIK','Księgowość'=>'ROLE_KSIEGOWOSC','Użytkownik'=>'ROLE_USER'
                ],
            ]);
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    if(is_array($rolesArray)){
                        // transform the array to a string
                        return count($rolesArray)? $rolesArray[0]: null;
                    }
                   return $rolesArray;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
