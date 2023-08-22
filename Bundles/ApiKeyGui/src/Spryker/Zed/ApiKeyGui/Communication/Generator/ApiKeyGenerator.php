<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Generator;

use Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig;
use Spryker\Zed\ApiKeyGui\Dependency\Service\ApiKeyGuiToUtilTextServiceInterface;

class ApiKeyGenerator implements ApiKeyGeneratorInterface
{
    /**
     * @var \Spryker\Zed\ApiKeyGui\Dependency\Service\ApiKeyGuiToUtilTextServiceInterface
     */
    protected ApiKeyGuiToUtilTextServiceInterface $utilTextService;

    /**
     * @var \Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig
     */
    protected ApiKeyGuiConfig $config;

    /**
     * @param \Spryker\Zed\ApiKeyGui\Dependency\Service\ApiKeyGuiToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig $config
     */
    public function __construct(
        ApiKeyGuiToUtilTextServiceInterface $utilTextService,
        ApiKeyGuiConfig $config
    ) {
        $this->utilTextService = $utilTextService;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        return $this->utilTextService->generateRandomString($this->config->getApiKeyLength());
    }
}
