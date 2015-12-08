<?php

namespace CPC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CPCUserBundle extends Bundle
{
	public function getParent()
  	{
    	return 'FOSUserBundle';
  	}
}
