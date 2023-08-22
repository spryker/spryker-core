<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Validator;

use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;

interface ApiKeyValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     * @param \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer $apiKeyCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function validate(
        ApiKeyTransfer $apiKeyTransfer,
        ApiKeyCollectionResponseTransfer $apiKeyCollectionResponseTransfer
    ): ApiKeyCollectionResponseTransfer;
}
