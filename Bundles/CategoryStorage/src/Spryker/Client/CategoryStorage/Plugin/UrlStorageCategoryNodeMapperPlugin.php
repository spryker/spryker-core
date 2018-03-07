<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 */
class UrlStorageCategoryNodeMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
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
        $idCategoryNode = $urlStorageTransfer->getFkResourceCategorynode();
        if ($idCategoryNode) {
            $resourceKey = $this->generateKey($idCategoryNode, $options['locale']);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @param int $idCategoryNode
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($idCategoryNode, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($this->getStoreName());
        $synchronizationDataTransfer->setLocale($locale);
        $synchronizationDataTransfer->setReference($idCategoryNode);

        return $this->getFactory()->getSynchronizationService()->getStorageKeyBuilder(CategoryStorageConstants::CATEGORY_NODE_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }
}
