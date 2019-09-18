<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Client;

use Spryker\Service\UtilNetwork\UtilNetworkServiceInterface;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;

class HttpClient extends AbstractHttpClient implements HttpClientInterface
{
    /**
     * @var string
     */
    protected $rawToken;

    /**
     * @var array
     */
    protected $tokenGenerationOptions;

    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param string $baseUrl
     * @param string $rawToken
     * @param bool $isAuthenticationEnabled Deprecated: Will be removed in the next major.
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     * @param \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface $utilNetworkService
     * @param array $tokenGenerationOptions
     * @param array $clientConfiguration
     *
     * @throws \RuntimeException
     */
    public function __construct(
        $baseUrl,
        $rawToken,
        $isAuthenticationEnabled,
        UtilTextServiceInterface $utilTextService,
        UtilNetworkServiceInterface $utilNetworkService,
        array $tokenGenerationOptions = [],
        array $clientConfiguration = []
    ) {
        parent::__construct($baseUrl, $utilNetworkService, $clientConfiguration);

        $this->rawToken = $rawToken;
        $this->utilTextService = $utilTextService;
        $this->tokenGenerationOptions = $tokenGenerationOptions;

        if ($isAuthenticationEnabled === false) {
            throw new RuntimeException('Setting $isAuthenticationEnabled to false is not allowed for security reasons.');
        }
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = [
            'Auth-Token' => $this->utilTextService->generateToken($this->rawToken, $this->tokenGenerationOptions),
        ];

        return $headers;
    }
}
