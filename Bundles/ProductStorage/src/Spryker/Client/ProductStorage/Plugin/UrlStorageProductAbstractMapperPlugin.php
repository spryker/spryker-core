<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Plugin;

use Generated\Shared\Transfer\SpyUrlTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

/**
 * @method \Spryker\Client\ProductStorage\ProductStorageFactory getFactory()
 */
class UrlStorageProductAbstractMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
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
        $idProductAbstract = $spyUrlTransfer->getFkResourceProductAbstract();
        if ($idProductAbstract) {
            $resourceKey = $this->generateKey($idProductAbstract, $options['locale']);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($idProductAbstract, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($this->getStoreName());
        $synchronizationDataTransfer->setLocale($locale);
        $synchronizationDataTransfer->setReference($idProductAbstract);

        return $this->getFactory()->getSynchronizationService()->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }
}
