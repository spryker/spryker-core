<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Validator;

use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;

class ApiKeyValidator implements ApiKeyValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface>
     */
    protected array $validators;

    /**
     * @param array<\Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface> $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer
     * @param \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer $apiKeyCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer
     */
    public function validate(
        ApiKeyTransfer $apiKeyTransfer,
        ApiKeyCollectionResponseTransfer $apiKeyCollectionResponseTransfer
    ): ApiKeyCollectionResponseTransfer {
        foreach ($this->validators as $validator) {
            $apiKeyCollectionResponseTransfer = $validator->validate($apiKeyTransfer, $apiKeyCollectionResponseTransfer);
        }

        return $apiKeyCollectionResponseTransfer;
    }
}
