<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Validator\Util;

use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface ErrorAdderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param string $error
     * @param array<string, int|string> $params
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function addError(
        ErrorCollectionTransfer $errorCollectionTransfer,
        string $error,
        array $params = []
    ): ErrorCollectionTransfer;
}
