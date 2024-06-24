<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Reader;

use Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionConditionsTransfer;
use Generated\Shared\Transfer\SalesMerchantCommissionCriteriaTransfer;
use Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionRepositoryInterface;

class SalesMerchantCommissionReader implements SalesMerchantCommissionReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionRepositoryInterface
     */
    protected SalesMerchantCommissionRepositoryInterface $salesMerchantCommissionRepository;

    /**
     * @param \Spryker\Zed\SalesMerchantCommission\Persistence\SalesMerchantCommissionRepositoryInterface $salesMerchantCommissionRepository
     */
    public function __construct(
        SalesMerchantCommissionRepositoryInterface $salesMerchantCommissionRepository
    ) {
        $this->salesMerchantCommissionRepository = $salesMerchantCommissionRepository;
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return list<\Generated\Shared\Transfer\SalesMerchantCommissionTransfer>
     */
    public function getSalesMerchantCommissionsBySalesOrderItemIds(array $salesOrderItemIds): array
    {
        $salesMerchantCommissionConditionsTransfer = (new SalesMerchantCommissionConditionsTransfer())
            ->setSalesOrderItemIds($salesOrderItemIds);

        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->setSalesMerchantCommissionConditions($salesMerchantCommissionConditionsTransfer);

        return $this->salesMerchantCommissionRepository
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer)
            ->getSalesMerchantCommissions()
            ->getArrayCopy();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\SalesMerchantCommissionCollectionTransfer
     */
    public function getSalesMerchantCommissionsByIdSalesOrder(int $idSalesOrder): SalesMerchantCommissionCollectionTransfer
    {
        $salesMerchantCommissionConditionsTransfer = (new SalesMerchantCommissionConditionsTransfer())
            ->addIdSalesOrder($idSalesOrder);
        $salesMerchantCommissionCriteriaTransfer = (new SalesMerchantCommissionCriteriaTransfer())
            ->setSalesMerchantCommissionConditions($salesMerchantCommissionConditionsTransfer);

        return $this->salesMerchantCommissionRepository
            ->getSalesMerchantCommissionCollection($salesMerchantCommissionCriteriaTransfer);
    }
}
