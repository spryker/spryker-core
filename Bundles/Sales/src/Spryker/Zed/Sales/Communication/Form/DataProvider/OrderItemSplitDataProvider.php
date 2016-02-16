<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;

class OrderItemSplitDataProvider
{

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer
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
