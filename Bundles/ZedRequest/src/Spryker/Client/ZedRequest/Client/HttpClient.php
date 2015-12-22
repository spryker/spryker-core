<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\ZedRequest\Client;

use Spryker\Client\Auth\AuthClientInterface;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;

class HttpClient extends AbstractHttpClient
{

    /**
     * @var string
     */
    protected $rawToken;

    /**
     * @param AuthClientInterface $authClient
     * @param string $baseUrl
     * @param string $rawToken
     */
    public function __construct(
        AuthClientInterface $authClient,
        $baseUrl,
        $rawToken
    ) {
        parent::__construct($authClient, $baseUrl);
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
