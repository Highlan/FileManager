<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class FileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'file',
                FileType::class,
                [
                    'mapped'   => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize'  => '8M'
                        ])
                    ]
                ]
            )
            ;
    }
}