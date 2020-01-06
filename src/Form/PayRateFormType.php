<?php

namespace App\Form;

use App\Entity\PayRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayRateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('obtainFrom',DateType::class,['widget'=>'single_text','label_attr'=>['class'=>'col-sm-2 col-form-label']])
            ->add('ratePerHour',MoneyType::class,['currency'=>'PLN','label_attr'=>['class'=>'col-sm-2 col-form-label']])
            ->add('overtimeRate',MoneyType::class,['currency'=>'PLN','label_attr'=>['class'=>'col-sm-2 col-form-label']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PayRate::class,
        ]);
    }
}
