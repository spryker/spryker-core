<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\ProductLabelTransfer;

interface ProductLabelEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function createProductLabel(ProductLabelTransfer $productLabelTransfer): ProductLabelTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return array<string>
     */
    public function updateProductLabel(ProductLabelTransfer $productLabelTransfer): array;

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabel(int $idProductLabel): void;

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelStoreRelations(int $idProductLabel): void;

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelLocalizedAttributes(int $idProductLabel): void;

    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function deleteProductLabelProductAbstractRelations(int $idProductLabel, array $productAbstractIds = []): void;

    /**
     * @param array<int> $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function removeProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void;

    /**
     * @param array<int> $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function createProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void;
}
