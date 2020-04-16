<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;

interface ProductLabelStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer
     *
     * @return void
     */
    public function saveProductAbstractLabelStorage(ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     * @param string $locale
     *
     * @return void
     */
    public function saveProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer, string $locale): void;

    /**
     * @return void
     */
    public function deleteAllProductLabelDictionaryStorageEntities(): void;

    /**
     * @param int $productAbstractId
     *
     * @return void
     */
    public function deleteProductAbstractLabelStorageByProductAbstractId(int $productAbstractId): void;

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function deleteProductAbstractLabelStorageEntitiesByProductAbstractIds(array $productAbstractIds): void;

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     *
     * @return void
     */
    public function createProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     *
     * @return void
     */
    public function updateProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void;

    /**
     * @param int $idProductLabelDictionary
     *
     * @return void
     */
    public function deleteProductLabelDictionaryStorageById(int $idProductLabelDictionary): void;
}
