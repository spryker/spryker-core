<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Mapper;

use Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class MerchantCommissionMapper implements MerchantCommissionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer
     */
    public function mapOrderTransferToMerchantCommissionCalculationRequestTransfer(
        OrderTransfer $orderTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationRequestTransfer {
        $merchantCommissionCalculationRequestTransfer
            ->setStore((new StoreTransfer())->setName($orderTransfer->getStoreOrFail()))
            ->setCurrency($orderTransfer->getCurrencyOrFail())
            ->setIdSalesOrder($orderTransfer->getIdSalesOrderOrFail())
            ->setPriceMode($orderTransfer->getPriceModeOrFail());

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $merchantCommissionCalculationRequestTransfer->addItem(
                (new MerchantCommissionCalculationRequestItemTransfer())
                    ->fromArray($itemTransfer->toArray(), true)
                    ->setIdSalesOrder($itemTransfer->getFkSalesOrderOrFail()),
            );
        }

        return $merchantCommissionCalculationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    public function mapMerchantCommissionCalculationItemTransferToSalesMerchantCommissionTransfers(
        MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer
    ): array {
        $salesMerchantCommissionTransfers = [];

        foreach ($merchantCommissionCalculationItemTransfer->getMerchantCommissions() as $merchantCommissionTransfer) {
            $salesMerchantCommissionTransfers[] = (new SalesMerchantCommissionTransfer())
                ->fromArray($merchantCommissionTransfer->toArray(), true)
                ->fromArray($merchantCommissionCalculationItemTransfer->toArray(), true)
                ->setUuid(null);
        }

        return $salesMerchantCommissionTransfers;
    }
}
