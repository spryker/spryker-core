<?php

namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByItemInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;

class DecrementStock extends AbstractCommand implements
    CommandByItemInterface
{

    /**
     * @param SpySalesOrderItem $orderItem
     * @param ReadOnlyArrayObject $data
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        if (!$orderItem->getOrder()->getIsTest()) {
            Locator::getInstance()->stockSalesConnector()->updateStockPlugin()->decrementPhysicalStock($orderItem->getSku()); //TODO refactor
//            $productEntity = $this->facadeCatalog->getProductBySku($orderItem->getSku());
//            $this->facadeStock->decrementStockProduct($productEntity);
        }
    }

}
