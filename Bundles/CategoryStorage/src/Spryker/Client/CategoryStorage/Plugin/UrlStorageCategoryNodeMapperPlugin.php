<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Plugin;

use Generated\Shared\Transfer\SpyUrlTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\Kernel\Store;

class UrlStorageCategoryNodeMapperPlugin implements UrlStorageResourceMapperPluginInterface
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
        if ($spyUrlTransfer->getFkResourceCategorynode()) {
            $resourceKey = sprintf(
                '%s.%s.resource.categorynode.%d',
                strtolower($storeName),
                $options['locale'],
                $spyUrlTransfer->getFkResourceCategorynode()
            );
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType('categorynode');
        }

        return $urlStorageResourceMapTransfer;
    }

}
