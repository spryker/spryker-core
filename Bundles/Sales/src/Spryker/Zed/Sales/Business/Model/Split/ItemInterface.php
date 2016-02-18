<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Split;

interface ItemInterface
{

    /**
     * @param int $idSalesOrderItem
     * @param int $quantityToSplit
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ItemSplitResponseTransfer
     */
    public function split($idSalesOrderItem, $quantityToSplit);

}
