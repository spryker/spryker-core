<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business;

interface ProductStorageFacadeInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
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
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstracts(array $productAbstractIds);

    /**
     * Specification:
     * - Finds product concrete localized entities by product abstract ids.
     * - Finds product concrete storage entities by product abstract ids.
     * - Deletes product concrete storage entities if no localized entities are found.
     * - Expands ProductConcreteStorageTransfer collection with a stack of `ProductConcreteStorageCollectionExpanderPluginInterface`.
     * - Publishes product concrete storage collection to storage.
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
}
