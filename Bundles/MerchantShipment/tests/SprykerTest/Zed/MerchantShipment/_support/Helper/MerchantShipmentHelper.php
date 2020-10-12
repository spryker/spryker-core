<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantShipment\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantShipmentBuilder;
use Generated\Shared\Transfer\MerchantShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantShipmentHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantShipmentTransfer
     */
    public function haveMerchantShipment(array $seedData = []): MerchantShipmentTransfer
    {
        /** @var \Generated\Shared\Transfer\MerchantShipmentTransfer $merchantShipmentTransfer */
        $merchantShipmentTransfer = (new MerchantShipmentBuilder($seedData))->build();
        //$merchantShipmentTransfer->fromArray($seedData, true);

        $merchantShipment = new SpySalesShipment();
        $merchantShipment->setFkSalesOrder(1);
        $merchantShipment->fromArray($merchantShipmentTransfer->toArray());
        $merchantShipment->save();

        $merchantShipmentTransfer->fromArray($merchantShipment->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantShipmentTransfer) {
            $this->getSalesShipmentQuery()->filterByIdSalesShipment($merchantShipmentTransfer->getIdSalesShipment())->delete();
        });

        return $merchantShipmentTransfer;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    protected function getSalesShipmentQuery(): SpySalesShipmentQuery
    {
        return SpySalesShipmentQuery::create();
    }
}
