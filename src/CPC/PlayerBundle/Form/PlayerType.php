<?php

namespace CPC\PlayerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class PlayerType extends AbstractType
{
    protected $videogame;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $videogame = $this->videogame;

        $builder
            ->add('nickname', 'text', array(
                    'label' => 'Pseudo In Game'))
            ->add('team', 'entity', array(
                    'class' => 'CPCTeamBundle:Team',
                    'required' => false,
                    'choice_label' => 'name',
                    'query_builder' => function(EntityRepository $repository) use ($videogame) { 
                        return $repository->createQueryBuilder('u')
                                            ->where("u.videogame = :videogame")
                                            ->setParameter('videogame', $videogame)
                                            ->orderBy('u.name', 'ASC');
                    },
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

    public function getName()
    {
        return 'player';
    }

    public function __construct ($videogame)
    {
        $this->videogame = $videogame;
    }
}