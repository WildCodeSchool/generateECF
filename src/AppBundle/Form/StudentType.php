<?php

namespace AppBundle\Form;

use AppBundle\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('validateActivityOne')
            ->add('commActivityOne', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                )
            ))
            ->add('validateEvalSuppOne')
            ->add('commEvalSuppOne', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                )
            ))
            ->add('validateActivityTwo')
            ->add('commActivityTwo', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                )
            ))
            ->add('validateEvalSuppTwo')
            ->add('commEvalSuppTwo', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                )
            ))
            ->add('observationStudent', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                )
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Student'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_student';
    }


}
