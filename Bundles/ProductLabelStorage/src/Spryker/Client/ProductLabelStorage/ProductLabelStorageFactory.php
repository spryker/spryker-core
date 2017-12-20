<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory;
use Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReader;
use Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface;
use Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReader;
use Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface;
use Spryker\Shared\Kernel\Store;

class ProductLabelStorageFactory extends AbstractFactory
{
    /**
     * @return ProductAbstractLabelReaderInterface
     */
    public function createProductAbstractLabelStorageReader()
    {
        return new ProductAbstractLabelReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->createLabelDictionaryReader(),
            $this->getStore()
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
     * @return Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::STORE);
    }

    /**
     * @return LabelDictionaryReaderInterface
     */
    public function createLabelDictionaryReader()
    {
        return new LabelDictionaryReader($this->createDictionaryFactory());
    }

    /**
     * @return DictionaryFactory
     */
    protected function createDictionaryFactory()
    {
        return new DictionaryFactory();
    }
}
