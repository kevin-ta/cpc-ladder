<?php

namespace CPC\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class GameType extends AbstractType
{
    protected $videogame;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $videogame = $this->videogame;

        $builder
            ->add('team1', 'entity', array(
                    'class'    => 'CPCTeamBundle:Team',
                    'query_builder' => function(EntityRepository $repository) use ($videogame) { 
                        return $repository->createQueryBuilder('u')
                                            ->where("u.videogame = :videogame")
                                            ->orderBy('u.name', 'ASC')
                                            ->setParameter('videogame', $videogame);
                    },
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'label' => 'Equipe 1'))
            ->add('team2', 'entity', array(
                    'class'    => 'CPCTeamBundle:Team',
                    'query_builder' => function(EntityRepository $repository) use ($videogame) { 
                        return $repository->createQueryBuilder('u')
                                            ->where("u.videogame = :videogame")
                                            ->orderBy('u.name', 'ASC')
                                            ->setParameter('videogame', $videogame);
                    },
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'label' => 'Equipe 2'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CPC\GameBundle\Entity\Game',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'game';
    }

    public function __construct ($videogame)
    {
        $this->videogame = $videogame;
    }
}