<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Hasher;

use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Spryker\Zed\ApiKey\ApiKeyConfig;
use Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface;

class ApiKeyHasher implements ApiKeyHasherInterface
{
    /**
     * @var \Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface
     */
    protected ApiKeyToUtilTextServiceInterface $utilTextService;

    /**
     * @var \Spryker\Zed\ApiKey\ApiKeyConfig
     */
    protected ApiKeyConfig $config;

    /**
     * @param \Spryker\Zed\ApiKey\Dependency\Service\ApiKeyToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Zed\ApiKey\ApiKeyConfig $config
     */
    public function __construct(
        ApiKeyToUtilTextServiceInterface $utilTextService,
        ApiKeyConfig $config
    ) {
        $this->utilTextService = $utilTextService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return array<string>
     */
    public function getApiKeyHashes(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): array
    {
        $apiKeyConditionsTransfer = $apiKeyCriteriaTransfer->getApiKeyConditions();
        if ($apiKeyConditionsTransfer === null) {
            return [];
        }

        $apiKeys = $apiKeyConditionsTransfer->getApiKeys();
        if ($apiKeys === []) {
            return [];
        }

        $hashedKeys = [];
        $hashAlgorithm = $this->config->getHashAlgorithm();
        foreach ($apiKeys as $apiKey) {
            $hashedKeys[] = $this->utilTextService->hashValue($apiKey, $hashAlgorithm);
        }

        return $hashedKeys;
    }
}
