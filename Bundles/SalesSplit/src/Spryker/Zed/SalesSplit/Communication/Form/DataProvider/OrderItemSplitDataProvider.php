<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesSplit\Communication\Form\OrderItemSplitForm;

class OrderItemSplitDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
     *
     * @return array
     */
    public function getData(ItemTransfer $orderItemTransfer)
    {
        return [
            OrderItemSplitForm::FIELD_ID_ORDER_ITEM => $orderItemTransfer->getIdSalesOrderItem(),
            OrderItemSplitForm::FIELD_ID_ORDER => $orderItemTransfer->getFkSalesOrder(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
