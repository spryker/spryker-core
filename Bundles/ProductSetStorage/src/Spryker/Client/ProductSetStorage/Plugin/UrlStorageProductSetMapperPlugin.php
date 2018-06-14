<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Shared\ProductSetStorage\ProductSetStorageConstants;

/**
 * @method \Spryker\Client\ProductSetStorage\ProductSetStorageFactory getFactory()
 */
class UrlStorageProductSetMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
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
        $idProductSet = $urlStorageTransfer->getFkResourceProductSet();
        if ($idProductSet) {
            $resourceKey = $this->generateKey($idProductSet, $options['locale']);
            $urlStorageResourceMapTransfer->setResourceKey($resourceKey);
            $urlStorageResourceMapTransfer->setType(ProductSetStorageConstants::PRODUCT_SET_RESOURCE_NAME);
        }

        return $urlStorageResourceMapTransfer;
    }

    /**
     * @param int $idProductSet
     * @param string $locale
     *
     * @return string
     */
    protected function generateKey($idProductSet, $locale)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setLocale($locale);
        $synchronizationDataTransfer->setReference($idProductSet);

        return $this->getFactory()
            ->getSynchronizationService()
            ->getStorageKeyBuilder(ProductSetStorageConstants::PRODUCT_SET_RESOURCE_NAME)->generateKey($synchronizationDataTransfer);
    }
}
