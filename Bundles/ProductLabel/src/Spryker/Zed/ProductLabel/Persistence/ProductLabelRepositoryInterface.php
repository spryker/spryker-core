<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

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
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findProductLabelByNameProductLabel(string $labelName): ?ProductLabelTransfer;

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
}
