<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator;

use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;

interface SalesOrderItemValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function validate(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer;
}
