<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Client;

use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerFeature\Client\Auth\AuthClientInterface;
use SprykerFeature\Shared\ZedRequest\Client\AbstractHttpClient;

class HttpClient extends AbstractHttpClient
{

    /**
     * @var string
     */
    protected $rawToken;

    /**
     * @param FactoryInterface $factory
     * @param AuthClientInterface $authClient
     * @param string $baseUrl
     * @param string $rawToken
     */
    public function __construct(
        FactoryInterface $factory,
        AuthClientInterface $authClient,
        $baseUrl,
        $rawToken
    ) {
        parent::__construct($factory, $authClient, $baseUrl);
        $this->rawToken = $rawToken;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = [
            'Auth-Token' => $this->authClient->generateToken($this->rawToken),
        ];

        return $headers;
    }

}
