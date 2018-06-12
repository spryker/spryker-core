<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Synchronization\Business\Export\Exporter;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreator;
use Spryker\Zed\Synchronization\Business\Search\SynchronizationSearch;
use Spryker\Zed\Synchronization\Business\Storage\SynchronizationStorage;
use Spryker\Zed\Synchronization\Business\Validation\OutdatedValidator;
use Spryker\Zed\Synchronization\SynchronizationDependencyProvider;

/**
 * @method \Spryker\Zed\Synchronization\SynchronizationConfig getConfig()
 */
class SynchronizationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface
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
     * @return \Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface
     */
    public function createSearchManager()
    {
        return new SynchronizationSearch(
            $this->getSearchClient(),
            $this->createOutdatedValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Export\ExporterInterface
     */
    public function createExporter()
    {
        return new Exporter(
            $this->getQueueClient(),
            $this->createQueueMessageCreator(),
            $this->getSynchronizationDataPlugins(),
            $this->getConfig()->getSyncExportChunkSize()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Validation\OutdatedValidatorInterface
     */
    protected function createOutdatedValidator()
    {
        return new OutdatedValidator(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface
     */
    protected function createQueueMessageCreator()
    {
        return new QueueMessageCreator();
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientInterface
     */
    protected function getSearchClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface
     */
    protected function getQueueClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
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
        return $this->getProvidedDependency(SynchronizationDependencyProvider::PLUGINS_SYNCHRONIZATION_DATA);
    }
}
