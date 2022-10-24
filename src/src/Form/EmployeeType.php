<?php

namespace App\Form;

use App\Entity\EmployeeFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employeeName', TextType::class)
            ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'submit'],
            ])
            ->add('size', ChoiceType::class, [
                'choices' => [
                    '50' => 50,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmployeeFilter::class,
        ]);
    }
}
