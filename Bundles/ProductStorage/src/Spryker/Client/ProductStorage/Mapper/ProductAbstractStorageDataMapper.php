<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductConcreteViewExpanderExcluderPluginInterface;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

class ProductAbstractStorageDataMapper extends ProductStorageDataMapper
{
    /**
     * @param \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface $storageProductExpanderPlugin
     *
     * @return bool
     */
    protected function filterProductStorageExpanderPlugins(ProductViewExpanderPluginInterface $storageProductExpanderPlugin): bool
    {
        if ($storageProductExpanderPlugin instanceof ProductConcreteViewExpanderExcluderPluginInterface) {
            return false;
        }

        return true;
    }

    /**
     * @param array $productStorageData
     *
     * @return array
     */
    protected function filterAbstractProductVariantsData(array $productStorageData): array
    {
        return $productStorageData;
    }
}
