<?php

namespace CPC\PlayerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', 'text', array(
                  'label' => 'Pseudo In Game'))
            ->add('team', 'entity', array(
                  'class' => 'CPCTeamBundle:Team',
                  'required' => false,
                  'choice_label' => 'name',
                  'label' => 'Ton équipe ?'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CPC\PlayerBundle\Entity\Player',
            'csrf_protection' => false,
        ));
    }
}