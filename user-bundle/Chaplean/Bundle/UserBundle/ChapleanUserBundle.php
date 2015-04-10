<?php

namespace Chaplean\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ChapleanUserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
