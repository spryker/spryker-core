<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator\Util;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;

class ErrorAdder implements ErrorAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param string|int $entityIdentifier
     * @param string $error
     * @param array<string, int|string> $params
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function addError(
        ErrorCollectionTransfer $errorCollectionTransfer,
        int|string|null $entityIdentifier,
        string $error,
        array $params = []
    ): ErrorCollectionTransfer {
        return $errorCollectionTransfer->addError(
            $this->createErrorTransfer($entityIdentifier, $error, $params),
        );
    }

    /**
     * @param string|int $entityIdentifier
     * @param string $error
     * @param array<string, int|string> $parameters
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(
        string|int|null $entityIdentifier,
        string $error,
        array $parameters = []
    ): ErrorTransfer {
        return (new ErrorTransfer())
            ->setMessage($error)
            ->setParameters($parameters)
            ->setEntityIdentifier($entityIdentifier ? (string)$entityIdentifier : null);
    }
}
