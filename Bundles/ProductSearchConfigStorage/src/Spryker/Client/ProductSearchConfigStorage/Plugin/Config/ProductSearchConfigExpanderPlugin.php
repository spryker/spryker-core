<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSearchConfigStorage\Plugin\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface;
use Spryker\Shared\ProductSearchConfigStorage\ProductSearchConfigStorageConfig;

/**
 * @method \Spryker\Client\ProductSearchConfigStorage\ProductSearchConfigStorageFactory getFactory()
 */
class ProductSearchConfigExpanderPlugin extends AbstractPlugin implements SearchConfigExpanderPluginInterface
{
    /**
     * @return \Generated\Shared\Transfer\SearchConfigExtensionTransfer
     */
    public function getSearchConfigExtension()
    {
        $productSearchConfigExtensionTransfer = new SearchConfigExtensionTransfer();

        $key = $this->getKey();
        $data = $this->getProductSearchConfigExtensionData($key);

        if ($data) {
            $productSearchConfigExtensionTransfer->fromArray($data, true);
        }

        return $productSearchConfigExtensionTransfer;
    }

    /**
     * @return string
     */
    protected function getKey()
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        return $this->getFactory()
            ->getSynchronizationService()
            ->getStorageKeyBuilder(ProductSearchConfigStorageConfig::PRODUCT_SEARCH_CONFIG_EXTENSION_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function getProductSearchConfigExtensionData($key)
    {
        $data = $this
            ->getFactory()
            ->getStorageClient()
            ->get($key);

        return $data;
    }
}
