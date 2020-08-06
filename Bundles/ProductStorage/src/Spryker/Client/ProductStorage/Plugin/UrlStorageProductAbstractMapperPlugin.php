<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Generated\Shared\Transfer\UrlStorageResourceMapTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorage\ProductStorageConfig;
use Spryker\Client\UrlStorage\Dependency\Plugin\UrlStorageResourceMapperPluginInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

/**
 * @method \Spryker\Client\ProductStorage\ProductStorageFactory getFactory()
 */
class UrlStorageProductAbstractMapperPlugin extends AbstractPlugin implements UrlStorageResourceMapperPluginInterface
{
    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @var string|null
     */
    protected static $storeName;

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\UrlStorageResourceMapTransfer
     */
    public function map(UrlStorageTransfer $urlStorageTransfer, array $options = [])
    {
        $urlStorageResourceMapTransfer = new UrlStorageResourceMapTransfer();
        $idProductAbstract = $urlStorageTransfer->getFkResourceProductAbstract();
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
        if (ProductStorageConfig::isCollectorCompatibilityMode()) {
            $collectorDataKey = sprintf(
                '%s.%s.resource.product_abstract.%s',
                strtolower(Store::getInstance()->getStoreName()),
                strtolower($locale),
                $idProductAbstract
            );

            return $collectorDataKey;
        }
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setStore($this->getStoreName());
        $synchronizationDataTransfer->setLocale($locale);
        $synchronizationDataTransfer->setReference($idProductAbstract);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return string
     */
    protected function getStoreName(): string
    {
        if (static::$storeName === null) {
            static::$storeName = $this->getFactory()
                ->getStore()
                ->getStoreName();
        }

        return static::$storeName;
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->getFactory()
                ->getSynchronizationService()
                ->getStorageKeyBuilder(ProductStorageConstants::PRODUCT_ABSTRACT_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }
}
