<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Validator\Rule\SalesOrderItem;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;

interface SalesOrderItemValidatorRuleInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $itemTransfers): ErrorCollectionTransfer;
}
