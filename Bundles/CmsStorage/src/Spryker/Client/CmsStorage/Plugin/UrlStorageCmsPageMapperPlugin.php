<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsStorage\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\CmsStorage\CmsStorageConfig;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\CmsStorage\CmsStorageFactory getFactory()
 */
class UrlStorageCmsPageMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(UrlStorageTransfer $urlStorageTransfer, array $options = [])
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $idCmsPage = $urlStorageTransfer->getFkResourcePage();
        if ($idCmsPage) {
            $resourceKey = $this->generateKey($idCmsPage, $options['locale']);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(CmsStorageConstants::CMS_PAGE_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @param int $idCmsPage
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($idCmsPage, $locale)
    {
        if (CmsStorageConfig::isCollectorCompatibilityMode()) {
            $collectorDataKey = sprintf(
                '%s.%s.resource.page.%s',
                strtolower(Store::getInstance()->getStoreName()),
                strtolower($locale),
                $idCmsPage
            );

            return $collectorDataKey;
        }
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setLocale($locale);
        $synchronizationDataTransfer->setReference($idCmsPage);

        return $this->getFactory()
            ->getSynchronizationService()
            ->getStorageKeyBuilder(CmsStorageConstants::CMS_PAGE_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
