<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator;

use Generated\Shared\Transfer\ErrorTransfer;

class ValidationErrorCreator implements ValidationErrorCreatorInterface
{
    /**
     * @param string|int $entityIdentifier
     * @param string $errorMessageKey
     * @param array<string, mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    public function createValidationError(string|int $entityIdentifier, string $errorMessageKey, array $parameters = []): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage($errorMessageKey)
            ->setParameters($parameters)
            ->setEntityIdentifier((string)$entityIdentifier);
    }
}
