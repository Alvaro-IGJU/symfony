<?php

namespace App\Form;

use App\Entity\PersonaEntityValidation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use  Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use  Symfony\Component\Form\Extension\Core\Type\TextType;

class PersonaValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('correo', TextType::class)
            ->add('telefono', TextType::class)
            ->add('pais', ChoiceType::class, [
                'choices' =>[
                    'Seleccione' => 0,
                    'Chile' => 1,
                    'Perú' => 2,
                    'México' => 3,
                    'España' => 4,
                    'Bolivia' => 5,
                    'Venezuela' => 6,
                    'Costa Rica' => 7,
                    'Noruega' => 8,
                ], 
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonaEntityValidation::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
        ]);
    }
}
