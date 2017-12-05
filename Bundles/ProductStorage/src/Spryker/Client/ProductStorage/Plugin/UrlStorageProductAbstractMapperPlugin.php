<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Plugin;

use Generated\Shared\Transfer\SpyUrlTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\Kernel\Store;

class UrlStorageProductAbstractMapperPlugin implements UrlStorageResourceMapperPluginInterface
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
        if ($spyUrlTransfer->getFkResourceProductAbstract()) {
            $resourceKey = sprintf(
                '%s.%s.resource.product_abstract.%d',
                strtolower($storeName),
                $options['locale'],
                $spyUrlTransfer->getFkResourceProductAbstract()
            );
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType('product_abstract');
        }

        return $urlStorageResourceMapTransfer;
    }

}
