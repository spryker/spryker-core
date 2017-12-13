<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Plugin;

use Generated\Shared\Transfer\SpyUrlTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\Kernel\Store;

class UrlStorageCmsPageMapperPlugin implements UrlStorageResourceMapperPluginInterface
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
        if ($spyUrlTransfer->getFkResourcePage()) {
            $resourceKey = sprintf(
                '%s:%s:%s:%d',
                CmsStorageConstants::CMS_PAGE_RESOURCE_NAME,
                strtolower($storeName),
                $options['locale'],
                $spyUrlTransfer->getFkResourcePage()
            );
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(CmsStorageConstants::CMS_PAGE_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

}
