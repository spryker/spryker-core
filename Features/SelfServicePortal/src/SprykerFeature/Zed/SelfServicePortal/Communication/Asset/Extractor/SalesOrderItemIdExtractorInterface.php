<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Extractor;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesOrderItemIdExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<int>
     */
    public function extractSalesOrderItemIds(OrderTransfer $orderTransfer): array;
}
