<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Deleter;

use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface;

class ProductAbstractLabelStorageDeleter implements ProductAbstractLabelStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface
     */
    protected $productLabelStorageEntityManager;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
     */
    public function __construct(ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager)
    {
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\Writer\ProductAbstractLabelStorageWriter::writeProductAbstractLabelStorageCollectionByProductAbstractLabelEvents()} instead.
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        $this->productLabelStorageEntityManager->deleteProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);
    }
}
