<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Generated\Shared\Transfer\ProductViewTransfer;

class ProductStorageDataMapper implements ProductStorageDataMapperInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected $productAbstractStorageExpanderPlugins;

    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[] $storageProductExpanderPlugins
     */
    public function __construct(array $storageProductExpanderPlugins)
    {
        $this->productAbstractStorageExpanderPlugins = $storageProductExpanderPlugins;
    }

    /**
     * @param string $locale
     * @param array $productStorageData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData($locale, array $productStorageData, array $selectedAttributes = [])
    {
        $productViewTransfer = $this->createProductViewTransfer($productStorageData);
        $productViewTransfer->setSelectedAttributes($selectedAttributes);

        foreach ($this->productAbstractStorageExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $locale);
        }

        return $productViewTransfer;
    }

    /**
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductViewTransfer(array $productStorageData)
    {
        $productStorageTransfer = new ProductViewTransfer();
        $productStorageTransfer->fromArray($productStorageData, true);

        return $productStorageTransfer;
    }
}
