<?php

namespace CPC\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class GameType extends AbstractType
{
    protected $videogame;
    protected $team1;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $videogame = $this->videogame;
        $team1 = $this->team;

        $builder
            ->add('team2', 'entity', array(
                    'class'    => 'CPCTeamBundle:Team',
                    'query_builder' => function(EntityRepository $repository) use ($videogame, $team1) { 
                        return $repository->createQueryBuilder('u')
                                            ->where("u.videogame = :videogame")
                                            ->setParameter('videogame', $videogame)
                                            ->andWhere("u.name != :team")
                                            ->setParameter('team', $team1)
                                            ->orderBy('u.name', 'ASC');
                    },
                    'choice_label' => 'name',
                    'multiple' => false,
                    'expanded' => false,
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

    public function __construct ($videogame, $team1)
    {
        $this->videogame = $videogame;
        $this->team = $team1;
    }
}