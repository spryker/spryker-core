<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsDiscountConnector\Communication\Plugin\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Spryker\Zed\OmsDiscountConnector\Communication\OmsDiscountConnectorCommunicationFactory getFactory()
 */
class ReleaseUsedVoucherCodes extends AbstractCommand implements CommandByOrderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $voucherCodes = $this->getVoucherCodes($orderEntity);

        if (empty($voucherCodes)) {
            return [];
        }

        $this->getFactory()->getDiscountFacade()->releaseUsedVoucherCodes($voucherCodes);

        return [];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return string[]
     */
    protected function getVoucherCodes(SpySalesOrder $orderEntity)
    {
        $voucherCodes = [];
        foreach ($orderEntity->getDiscounts() as $discountEntity) {
            foreach ($discountEntity->getDiscountCodes() as $salesDiscountCodesEntity) {
                $code = $salesDiscountCodesEntity->getCode();
                $voucherCodes[$code] = $code;
            }
        }

        return $voucherCodes;
    }
}
