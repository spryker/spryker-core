<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage\Dictionary;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageDependencyProvider;

class DictionaryFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByIdProductLabel()
    {
        return new LabelDictionary(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createKeyByIdProductLabelStrategy()
        );
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByLocalizedName()
    {
        return new LabelDictionary(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createKeyByLocalizedNameStrategy()
        );
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByName()
    {
        return new LabelDictionary(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createKeyByNameStrategy()
        );
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\KeyStrategyInterface
     */
    protected function createKeyByIdProductLabelStrategy()
    {
        return new KeyByIdProductLabelStrategy();
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\KeyStrategyInterface
     */
    protected function createKeyByLocalizedNameStrategy()
    {
        return new KeyByLocalizedNameStrategy();
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\KeyStrategyInterface
     */
    protected function createKeyByNameStrategy()
    {
        return new KeyByNameStrategy();
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::STORE);
    }
}
