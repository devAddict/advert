<?php

namespace DA\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DAUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
