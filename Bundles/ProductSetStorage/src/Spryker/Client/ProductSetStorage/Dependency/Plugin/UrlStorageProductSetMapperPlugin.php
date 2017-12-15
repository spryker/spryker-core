<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Dependency\Plugin;

use Generated\Shared\Transfer\SpyUrlTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\Kernel\Store;

class UrlStorageProductSetMapperPlugin implements UrlStorageResourceMapperPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\SpyUrlTransfer $spyUrlTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(SpyUrlTransfer $spyUrlTransfer, $options = [])
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $storeName = Store::getInstance()->getStoreName();
        if ($spyUrlTransfer->getFkResourceProductSet()) {
            $resourceKey = sprintf(
                '%s.%s.resource.product_set.%d',
                strtolower($storeName),
                $options['locale'],
                $spyUrlTransfer->getFkResourceProductSet()
            );
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType('product_set');
        }

        return $urlStorageResourceMapTransfer;
    }

}
