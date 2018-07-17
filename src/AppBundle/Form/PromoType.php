<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trainer', ChoiceType::class, array(
                'choices' => $options['trainers']
            ))
            ->add('campusManager', TextType::class, array(
                'required' => true
            ))
            ->add('adress', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Reseigne l\'adresse exact de ton ecole, ex: 11 rue de Poissy, 75005, Paris'
                ),
                'required' => true
            ))
            ->add('Send', SubmitType::class)
    ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Promo',
            'trainers' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_promo';
    }


}
