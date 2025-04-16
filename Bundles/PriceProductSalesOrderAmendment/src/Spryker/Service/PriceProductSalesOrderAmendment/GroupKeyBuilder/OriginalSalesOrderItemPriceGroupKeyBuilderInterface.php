<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment\GroupKeyBuilder;

use Generated\Shared\Transfer\ItemTransfer;

interface OriginalSalesOrderItemPriceGroupKeyBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemPriceGroupKey(ItemTransfer $itemTransfer): string;
}
