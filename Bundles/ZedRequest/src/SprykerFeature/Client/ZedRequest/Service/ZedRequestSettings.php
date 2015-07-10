<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use Generated\Client\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Client\Auth\Service\AuthClientInterface;
use SprykerFeature\Shared\Auth\AuthConfig;
use SprykerFeature\Shared\Auth\Client\StaticToken;
use SprykerFeature\Shared\Library\Config;

class ZedRequestSettings
{

    /**
     * @var AuthClientInterface
     */
    private $authClient;

    /**
     * @param AuthClientInterface $authClient
     */
    public function __construct(AuthClientInterface $authClient)
    {
        $this->authClient = $authClient;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $authConfig = Config::get(AuthConfig::AUTH_DEFAULT_CREDENTIALS);
        $rawToken = $authConfig['yves_system']['token'];

        $headers['Auth-Token'] = $this->authClient->generateToken($rawToken);

        return $headers;
    }

}
