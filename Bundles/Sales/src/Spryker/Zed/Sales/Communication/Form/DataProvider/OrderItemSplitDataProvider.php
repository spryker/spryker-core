<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Communication\Form\OrderItemSplitForm;

class OrderItemSplitDataProvider
{

    /**
     * @param SpySalesOrderItem $orderItemEntity
     *
     * @return array
     */
    public function getData(SpySalesOrderItem $orderItemEntity)
    {
        return [
            OrderItemSplitForm::FIELD_ID_ORDER_ITEM => $orderItemEntity->getIdSalesOrderItem(),
            OrderItemSplitForm::FIELD_ID_ORDER => $orderItemEntity->getFkSalesOrder(),
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
