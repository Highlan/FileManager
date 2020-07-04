<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                null,
                [
                    'label' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 2, 'max' => 8]),
                    ],
                ]
            )
            ->add(
                'password',
                null,
                [
                    'label' => false,
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 2, 'max' => 8]),
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
