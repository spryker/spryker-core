<?php

namespace ProjectA\OpenStack;

use OpenCloud\Rackspace;

class Factory
{

    const SERVICE_CLOUD_LOAD_BALANCERS = 'cloudLoadBalancers';
    const LOCATION_LON = 'LON';

    public static function getLoadBalancerService()
    {
        return self::getClient()->loadBalancerService(self::SERVICE_CLOUD_LOAD_BALANCERS, self::LOCATION_LON);
    }

    /**
     * @return Rackspace
     */
    protected static function getClient()
    {
        return new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
            'username' => 'michael.kugele',
            'apiKey'   => '47a00583299742beb2c8407be70027a3'
        ));
    }
} 
