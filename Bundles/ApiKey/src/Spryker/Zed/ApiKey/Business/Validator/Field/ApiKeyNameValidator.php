<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Validator\Field;

use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface;

class ApiKeyNameValidator implements ApiKeyValidatorInterface
{
    /**
     * @var string
     */
    protected const ALPHANUMERIC_REGEX = '/^[a-z][a-z0-9]*$/i';

    /**
     * @var string
     */
    protected const ERROR_NAME_IS_MISSING = 'The key name is not provided.';

    /**
     * @var string
     */
    protected const ERROR_INVALID_NAME = 'The provided key name `%s` is not in valid format. It should only contain letters (a-z) and digits (0-9). Example: `YourExample123`.';

    /**
     * @var string
     */
    protected const KEY_NAME_PLACEHOLDER = '%s';

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
        if ($apiKeyTransfer->getName() === null) {
            $errorTransfer = (new ErrorTransfer())->setMessage(static::ERROR_NAME_IS_MISSING);

            return $apiKeyCollectionResponseTransfer->addError($errorTransfer);
        }

        if (preg_match(static::ALPHANUMERIC_REGEX, $apiKeyTransfer->getNameOrFail()) !== 0) {
            return $apiKeyCollectionResponseTransfer;
        }

        $errorTransfer = (new ErrorTransfer())
            ->setMessage(static::ERROR_INVALID_NAME)
            ->setParameters([
                static::KEY_NAME_PLACEHOLDER => $apiKeyTransfer->getName(),
            ]);

        $apiKeyCollectionResponseTransfer->addError($errorTransfer);

        return $apiKeyCollectionResponseTransfer;
    }
}
