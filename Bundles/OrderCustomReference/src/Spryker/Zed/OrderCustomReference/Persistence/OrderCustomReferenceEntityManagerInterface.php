<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Persistence;

use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;

interface OrderCustomReferenceEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function saveOrderCustomReference(int $idSalesOrder, string $orderCustomReference): OrderCustomReferenceResponseTransfer;
}
