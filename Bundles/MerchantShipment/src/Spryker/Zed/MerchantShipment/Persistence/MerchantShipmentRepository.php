<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Persistence;

use Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantShipment\Persistence\MerchantShipmentPersistenceFactory getFactory()
 */
class MerchantShipmentRepository extends AbstractRepository implements MerchantShipmentRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipment(MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer): ?ShipmentTransfer
    {
        $salesShipmentQuery = $this->getFactory()
            ->createSalesShipmentPropelQuery();

        $salesShipmentQuery = $this->filterSalesShipmentQuery($salesShipmentQuery, $merchantShipmentCriteriaTransfer);
        $salesShipmentQuery->leftJoinWithSpySalesOrderAddress()
            ->useSpySalesOrderAddressQuery()
                ->leftJoinCountry()
            ->endUse();

        $salesShipmentEntity = $salesShipmentQuery->findOne();

        if (!$salesShipmentEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantShipmentMapper()
            ->mapShipmentEntityToShipmentTransfer($salesShipmentEntity, new ShipmentTransfer());
    }

    /**
     * @phpstan-param \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery<mixed> $salesShipmentQuery
     *
     * @phpstan-return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery<mixed>
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery $salesShipmentQuery
     * @param \Generated\Shared\Transfer\MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    protected function filterSalesShipmentQuery(
        SpySalesShipmentQuery $salesShipmentQuery,
        MerchantShipmentCriteriaTransfer $merchantShipmentCriteriaTransfer
    ): SpySalesShipmentQuery {
        if ($merchantShipmentCriteriaTransfer->getMerchantReference()) {
            $salesShipmentQuery->filterByMerchantReference(
                $merchantShipmentCriteriaTransfer->getMerchantReference()
            );
        }

        if ($merchantShipmentCriteriaTransfer->getIdShipment()) {
            $salesShipmentQuery->filterByIdSalesShipment(
                $merchantShipmentCriteriaTransfer->getIdShipment()
            );
        }

        return $salesShipmentQuery;
    }
}
