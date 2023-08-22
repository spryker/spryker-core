<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Persistence;

use Generated\Shared\Transfer\ApiKeyTransfer;

interface ApiKeyEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyTransfer
     */
    public function createApiKey(ApiKeyTransfer $apiKeyTransfer): ApiKeyTransfer;

    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyTransfer
     */
    public function updateApiKey(ApiKeyTransfer $apiKeyTransfer): ApiKeyTransfer;

    /**
     * @param array<int> $apiKeyIds
     *
     * @return void
     */
    public function deleteApiKeys(array $apiKeyIds): void;
}
