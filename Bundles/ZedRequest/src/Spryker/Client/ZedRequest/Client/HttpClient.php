<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Client;

use Spryker\Client\Auth\AuthClientInterface;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

class HttpClient extends AbstractHttpClient implements HttpClientInterface
{

    /**
     * @var string
     */
    protected $rawToken;

    /**
     * @var int
     */
    protected $authenticationType;

     /**
     * @var array
     */
    protected $staticCredential;

    /**
     * @param \Spryker\Client\Auth\AuthClientInterface $authClient
     * @param string $baseUrl
     * @param string $rawToken
     * @param int $authenticationType
     * @param array $staticCredential
     */
    public function __construct(
        AuthClientInterface $authClient,
        $baseUrl,
        $rawToken,
        $authenticationType,
        array $staticCredential
    ) {
        parent::__construct($authClient, $baseUrl);
        $this->rawToken = $rawToken;
        $this->authenticationType = $authenticationType;
        $this->staticCredential = $staticCredential;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];

        if ($this->authenticationType === ZedRequestConstants::AUTHENTICATE_STATIC) {
            $headers = [
                ZedRequestConstants::AUTH_STATIC_USERNAME_HEADER => $this->staticCredential['username'],
                ZedRequestConstants::AUTH_STATIC_PASSWORD_HEADER => $this->staticCredential['password'],
           ];
        }

        if ($this->authenticationType === ZedRequestConstants::AUTHENTICATE_DYNAMIC) {
            $headers = [
                'Auth-Token' => $this->authClient->generateToken($this->rawToken),
            ];
        }

        return $headers;
    }

}
