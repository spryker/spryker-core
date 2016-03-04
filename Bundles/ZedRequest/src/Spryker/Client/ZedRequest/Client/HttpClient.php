<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Client;

use Spryker\Client\Auth\AuthClientInterface;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;

class HttpClient extends AbstractHttpClient implements HttpClientInterface
{

    /**
     * @var string
     */
    protected $rawToken;

    /**
     * @param \Spryker\Client\Auth\AuthClientInterface $authClient
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
