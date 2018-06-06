<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Synchronization\Business\Export\Exporter;
use Spryker\Zed\Synchronization\Business\Model\Search\SynchronizationSearch;
use Spryker\Zed\Synchronization\Business\Model\Storage\SynchronizationStorage;
use Spryker\Zed\Synchronization\Business\Model\Validation\OutdatedValidator;
use Spryker\Zed\Synchronization\SynchronizationDependencyProvider;

/**
 * @method \Spryker\Zed\Synchronization\SynchronizationConfig getConfig()
 */
class SynchronizationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Synchronization\Business\Model\SynchronizationInterface
     */
    public function createStorageManager()
    {
        return new SynchronizationStorage(
            $this->getStorageClient(),
            $this->getUtilEncodingService(),
            $this->createOutdatedValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Model\SynchronizationInterface
     */
    public function createSearchManager()
    {
        return new SynchronizationSearch(
            $this->getSearchClient(),
            $this->createOutdatedValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Export\Exporter
     */
    public function createExporter()
    {
        return new Exporter($this->getSynchronizationDataPlugins());
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Model\Validation\OutdatedValidatorInterface
     */
    protected function createOutdatedValidator()
    {
        return new OutdatedValidator(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchInterface
     */
    protected function getSearchClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]
     */
    protected function getSynchronizationDataPlugins()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::SYNCHRONIZATION_DATA_PLUGINS);
    }
}
