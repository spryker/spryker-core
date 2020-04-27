<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Deleter;

use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface;

class ProductLabelDictionaryStorageDeleter implements ProductLabelDictionaryStorageDeleterInterface
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
     * @return void
     */
    public function deleteProductLabelDictionaryStorageCollection(): void
    {
        $this->productLabelStorageEntityManager->deleteAllProductLabelDictionaryStorageEntities();
    }
}
