<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;

interface ProductStorageFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstracts(array $productAbstractIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function publishConcreteProducts(array $productIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishConcreteProducts(array $productIds);

    /**
     * Specification:
     * - Expands the provided `ProductAbstractStorage` transfer object.
     * - Expects `ProductAbstractStorage.attributeMap.productConcreteIds` to be provided.
     * - Populates `ProductAbstractStorage.attributeMap.attributeVariantCollection` using provided product concretes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expandWithAttributeVariantCollection(
        ProductAbstractStorageTransfer $productAbstractStorageTransfer
    ): ProductAbstractStorageTransfer;
}
