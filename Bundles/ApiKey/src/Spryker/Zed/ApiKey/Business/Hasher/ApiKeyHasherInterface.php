<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Hasher;

use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;

interface ApiKeyHasherInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer
     *
     * @return array<string>
     */
    public function getApiKeyHashes(ApiKeyCriteriaTransfer $apiKeyCriteriaTransfer): array;
}
