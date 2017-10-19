<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSearch\Plugin\Config;

use Generated\Shared\Transfer\SearchConfigExtensionTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductSearch\ProductSearchFactory getFactory()
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
        $currentLocale = $this->getCurrentLocale();

        $key = $this
            ->getFactory()
            ->createProductSearchConfigExtensionKeyBuilder()
            ->generateKey([], $currentLocale);

        return $key;
    }

    /**
     * @return string
     */
    protected function getCurrentLocale()
    {
        $locale = $this
            ->getFactory()
            ->getStore()
            ->getCurrentLocale();

        return $locale;
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
