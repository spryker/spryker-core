<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Header\AuthToken;

use Spryker\Client\ZedRequest\ZedRequestConfig;
use Spryker\Service\UtilText\UtilTextServiceInterface;

class AuthToken implements AuthTokenInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestConfig
     */
    protected $config;

    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestConfig $config
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(ZedRequestConfig $config, UtilTextServiceInterface $utilTextService)
    {
        $this->config = $config;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->utilTextService->generateToken($this->config->getRawToken(), $this->config->getTokenOptions());
    }
}
