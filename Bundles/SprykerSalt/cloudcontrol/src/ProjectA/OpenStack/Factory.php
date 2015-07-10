<?php

namespace ProjectA\OpenStack;

use OpenCloud\Rackspace;

class Factory
{

    const SERVICE_CLOUD_LOAD_BALANCERS = 'cloudLoadBalancers';
    const LOCATION_LON = 'LON';

    /**
     * @param null|string $username
     * @param null|string $apiKey
     *
     * @return \OpenCloud\LoadBalancer\Service
     */
    public static function getLoadBalancerService($username = null, $apiKey = null)
    {
        return self::getClient($username, $apiKey)->loadBalancerService(self::SERVICE_CLOUD_LOAD_BALANCERS, self::LOCATION_LON);
    }

    /**
     * @param null|string $username
     * @param null|string $apiKey
     *
     * @return Rackspace
     */
    protected static function getClient($username = null, $apiKey = null)
    {
        if (null === $username || null === $apiKey) {
            $config = parse_ini_file(__DIR__ . '/../../../config.ini');
            $username = $config['username'];
            $apiKey = $config['apiKey'];
        }

        return new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, [
            'username' => $username,
            'apiKey' => $apiKey,
        ]);
    }

}
