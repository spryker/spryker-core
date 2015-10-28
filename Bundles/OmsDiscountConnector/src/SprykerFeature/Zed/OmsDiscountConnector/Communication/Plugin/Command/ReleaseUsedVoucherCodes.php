<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\OmsDiscountConnector\Communication\Plugin\Command;

use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use SprykerFeature\Zed\OmsDiscountConnector\Communication\OmsDiscountConnectorDependencyContainer;

/**
 * @method OmsDiscountConnectorDependencyContainer getDependencyContainer()
 */
class ReleaseUsedVoucherCodes extends AbstractCommand implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrder[] $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array $returnArray
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $voucherCodes = $this->getVoucherCodes($orderEntity);

        if (empty($voucherCodes)) {
            return [];
        }

        $this->getDependencyContainer()->createDiscountFacade()->releaseUsedVoucherCodes($voucherCodes);
    }

    /**
     * @param SpySalesOrder $orderEntity
     *
     * @return array
     */
    protected function getVoucherCodes(SpySalesOrder $orderEntity)
    {
        $voucherCodes = [];
        foreach ($orderEntity->getDiscounts() as $discountEntity) {
            foreach ($discountEntity->getDiscountCodes() as $salesDiscountCodesEntity) {
                $voucherCodes[$salesDiscountCodesEntity->getCode()] = $salesDiscountCodesEntity->getCode();
            }
        }
        return $voucherCodes;
    }
}
