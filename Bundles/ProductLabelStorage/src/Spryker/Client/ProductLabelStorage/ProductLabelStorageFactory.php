<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory;
use Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReader;
use Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReader;

class ProductLabelStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface
     */
    public function createProductAbstractLabelStorageReader()
    {
        return new ProductAbstractLabelReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->createLabelDictionaryReader(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductLabelStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface
     */
    public function createLabelDictionaryReader()
    {
        return new LabelDictionaryReader($this->createDictionaryFactory());
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory
     */
    protected function createDictionaryFactory()
    {
        return new DictionaryFactory();
    }
}
