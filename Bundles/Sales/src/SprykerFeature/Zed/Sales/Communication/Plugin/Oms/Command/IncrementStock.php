<?php
namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByItemInterface;

class IncrementStock extends AbstractCommand implements
    CommandByItemInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItemEntity
     * @param \SprykerFeature_Zed_Library_StateMachine_Context $context
     * @return mixed|void
     */
    public function run(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        if (!$orderItem->getOrder()->getIsTest()) {
//            $productEntity = $this->facadeCatalog->getProductBySku($orderItem->getSku());
//            $this->facadeStock->incrementStockProduct($productEntity);
            Locator::getInstance()->stockSalesConnector()->updateStockPlugin()->incrementPhysicalStock($orderItem->getSku()); //TODO refactor
        }
    }
}
