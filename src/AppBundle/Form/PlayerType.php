<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', 'text', array(
                'disabled' => $options['is_edit']
            ))
            ->add('position', 'choice', array(
                'choices' => array(
                    1 => 'Point Guard',
                    2 => 'Shooting Guard',
                    3 => 'Small Forward',
                    4 => 'Power Forward',
                    5 => 'Center'
                )
            ))
            ->add('tagLine', 'textarea');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
               'data_class' => 'AppBundle\Entity\Player',
                'is_edit' => false
            ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_player_type';
    }

    public function getName()
    {
        return 'player';
    }
}
