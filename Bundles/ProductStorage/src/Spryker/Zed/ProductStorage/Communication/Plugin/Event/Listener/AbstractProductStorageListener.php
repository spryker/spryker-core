<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 */
class AbstractProductStorageListener extends AbstractPlugin
{
    /**
     * @var array<int>
     */
    protected static $publishedProductAbstractIds = [];

    /**
     * @var array<int>
     */
    protected static $unpublishedProductAbstractIds = [];

    /**
     * @var string
     */
    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    protected function publishAbstractProducts(array $productAbstractIds)
    {
        $productAbstractIds = array_values(array_unique(array_diff($productAbstractIds, static::$publishedProductAbstractIds)));
        if ($productAbstractIds) {
            $this->getFacade()->publishAbstractProducts($productAbstractIds);
        }
        static::$publishedProductAbstractIds = array_merge(static::$publishedProductAbstractIds, $productAbstractIds);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    protected function unpublishProductAbstracts(array $productAbstractIds)
    {
        $productAbstractIds = array_values(array_unique(array_diff($productAbstractIds, static::$unpublishedProductAbstractIds)));
        if ($productAbstractIds) {
            $this->getFacade()->unpublishProductAbstracts($productAbstractIds);
        }
        static::$unpublishedProductAbstractIds = array_merge(static::$unpublishedProductAbstractIds, $productAbstractIds);
    }
}
