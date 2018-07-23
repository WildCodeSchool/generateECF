<?php

namespace AppBundle\Form;

use AppBundle\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('validateActivityOne', CheckboxType::class, array(
                'label' => "Validation activitee 1",
                'required' => false
            ))
            ->add('commActivityOne', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                ),
                'label' => "Commentaire pour l'activitee type 1",
                'required' => false
            ))
            ->add('validateActivityTwo', CheckboxType::class, array(
                'label' => "Validation activitee 2",
                'required' => false
            ))
            ->add('commActivityTwo', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                ),
                'label' => "Commentaire pour l'activitee type 2",
                'required' => false
            ))
            ->add('observationStudent', TextareaType::class, array(
                'attr' => array(
                    'maxlength' => 815,
                ),
                'label' => "Avis general sur l'eleve"
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
