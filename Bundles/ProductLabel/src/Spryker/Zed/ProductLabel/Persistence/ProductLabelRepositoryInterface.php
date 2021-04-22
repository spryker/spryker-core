<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

interface ProductLabelRepositoryInterface
{
    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelById(int $idProductLabel): ?ProductLabelTransfer;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function getProductLabelProductAbstractsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function getProductLabelProductAbstractsByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param string $productLabelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByName(string $productLabelName): ?ProductLabelTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function getAllProductLabelsSortedByPosition(): array;

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function getProductLabelsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
     */
    public function getActiveLabelsByCriteria(ProductLabelCriteriaTransfer $productLabelCriteriaTransfer): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductLabelIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getActiveProductLabelIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdProductLabel(int $idProductLabel): StoreRelationTransfer;

    /**
     * @param int $idProductLabel
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractTransfer[]
     */
    public function getProductAbstractRelationsByIdProductLabelAndProductAbstractIds(int $idProductLabel, array $productAbstractIds): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int
     */
    public function countProductLabelsByIdProductAbstract(int $idProductAbstract): int;
}
