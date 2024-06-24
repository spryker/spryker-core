<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\SalesMerchantCommission\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\SalesMerchantCommissionBuilder;
use Generated\Shared\Transfer\SalesMerchantCommissionTransfer;
use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommission;
use Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SalesMerchantCommissionHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionTransfer
     */
    public function haveSalesMerchantCommission(array $seed = []): SalesMerchantCommissionTransfer
    {
        $salesMerchantCommissionTransfer = (new SalesMerchantCommissionBuilder($seed))->build();
        $salesMerchantCommissionEntity = (new SpySalesMerchantCommission())
            ->fromArray($salesMerchantCommissionTransfer->toArray())
            ->setFkSalesOrder($salesMerchantCommissionTransfer->getIdSalesOrder())
            ->setFkSalesOrderItem($salesMerchantCommissionTransfer->getIdSalesOrderItem());

        $salesMerchantCommissionEntity->save();
        $salesMerchantCommissionTransfer->fromArray($salesMerchantCommissionEntity->toArray(), true);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesMerchantCommissionEntity): void {
            $this->deleteSalesMerchantCommission($salesMerchantCommissionEntity->getIdSalesMerchantCommission());
        });

        return $salesMerchantCommissionTransfer;
    }

    /**
     * @param int $idSalesMerchantCommission
     *
     * @return void
     */
    protected function deleteSalesMerchantCommission(int $idSalesMerchantCommission): void
    {
        $salesMerchantCommissionEntity = $this->getSalesMerchantCommissionQuery()
            ->findOneByIdSalesMerchantCommission($idSalesMerchantCommission);

        if ($salesMerchantCommissionEntity) {
            $salesMerchantCommissionEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\SalesMerchantCommission\Persistence\SpySalesMerchantCommissionQuery
     */
    protected function getSalesMerchantCommissionQuery(): SpySalesMerchantCommissionQuery
    {
        return SpySalesMerchantCommissionQuery::create();
    }
}
