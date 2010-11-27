<?php

namespace Application\DevoriginBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DevoriginBundle extends Bundle
{
    public function boot()
    {
        //Setting the app to the french locale by default
        //Dates will be in french, w00t
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
    }

}
