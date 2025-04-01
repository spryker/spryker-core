<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Persistence;

use Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

interface SspServiceManagementRepositoryInterface
{
    /**
     * @param list<int> $productConcreteIds
     *
     * @return array<int, list<int>>
     */
    public function getShipmentTypeIdsGroupedByIdProductConcrete(array $productConcreteIds): array;

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTypeCollectionTransfer
     */
    public function getProductAbstractTypeCollection(): ProductAbstractTypeCollectionTransfer;

    /**
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>
     */
    public function getProductAbstractTypesByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>
     */
    public function getProductAbstractTypesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery;

    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractType>
     */
    public function findProductAbstractTypesByProductAbstractIds(array $productAbstractIds): array;
}
