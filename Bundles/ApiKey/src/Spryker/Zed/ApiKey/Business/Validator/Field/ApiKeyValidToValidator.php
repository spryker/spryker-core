<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKey\Business\Validator\Field;

use DateTime;
use Generated\Shared\Transfer\ApiKeyCollectionResponseTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\ApiKey\Business\Validator\ApiKeyValidatorInterface;

class ApiKeyValidToValidator implements ApiKeyValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_VALID_TO_DATE = 'The provided `valid to` date `%s` already expired. Use another one and try again.';

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
        $validTo = $apiKeyTransfer->getValidTo();

        if ($validTo === null) {
            return $apiKeyCollectionResponseTransfer;
        }

        if ($this->isValidToDate($validTo) === false) {
            $errorTransfer = (new ErrorTransfer())->setMessage(sprintf(static::ERROR_VALID_TO_DATE, $validTo));

            $apiKeyCollectionResponseTransfer->addError($errorTransfer);
        }

        return $apiKeyCollectionResponseTransfer;
    }

    /**
     * @param string $date
     *
     * @return bool
     */
    protected function isValidToDate(string $date): bool
    {
        return new DateTime() < new DateTime($date);
    }
}
