<?php 

namespace CPC\VideoGameBundle\Twig;

use Doctrine\ORM\EntityManager;

class VideoGameHelper extends \Twig_Extension
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return array(
			'videoGames' => new \Twig_Function_Method($this, 'videoGames'),
        );
    }

    public function videoGames()
    {
        return $this->em->getRepository('CPCVideoGameBundle:VideoGame')->findAll();
    }

    public function getName()
    {
        return 'cpc_video_game_twig_video_game_helper';
    }
}
