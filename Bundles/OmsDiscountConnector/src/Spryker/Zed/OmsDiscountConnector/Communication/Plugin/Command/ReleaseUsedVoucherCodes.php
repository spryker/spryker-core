<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\OmsDiscountConnector\Communication\Plugin\Command;

use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use Spryker\Zed\OmsDiscountConnector\Communication\OmsDiscountConnectorCommunicationFactory;

/**
 * @method OmsDiscountConnectorCommunicationFactory getFactory()
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

        $this->getFactory()->getDiscountFacade()->releaseUsedVoucherCodes($voucherCodes);
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
