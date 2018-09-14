<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Synchronization\Business\Export\ExporterPluginResolver;
use Spryker\Zed\Synchronization\Business\Export\QueryContainerExporter;
use Spryker\Zed\Synchronization\Business\Export\RepositoryExporter;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreator;
use Spryker\Zed\Synchronization\Business\QueueMessageManager\QueueMessageManager;
use Spryker\Zed\Synchronization\Business\QueueMessageManager\QueueMessageManagerInterface;
use Spryker\Zed\Synchronization\Business\QueueMessageProcessor\BulkQueueMessageProcessor;
use Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessor;
use Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface;
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
     * @return \Spryker\Zed\Synchronization\Business\Export\RepositoryExporter
     */
    public function createRepositoryExporter()
    {
        return new RepositoryExporter(
            $this->getQueueClient(),
            $this->createQueueMessageCreator(),
            $this->getConfig()->getSyncExportChunkSize()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Export\QueryContainerExporter
     */
    public function createQueryContainerExporter()
    {
        return new QueryContainerExporter(
            $this->getQueueClient(),
            $this->createQueueMessageCreator(),
            $this->getConfig()->getSyncExportChunkSize()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\Export\ExporterPluginResolver
     */
    public function createExporterPluginResolver()
    {
        return new ExporterPluginResolver(
            $this->getSynchronizationDataPlugins(),
            $this->createQueryContainerExporter(),
            $this->createRepositoryExporter()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\QueueMessageManager\QueueMessageManagerInterface
     */
    public function createSearchQueueMessageManager(): QueueMessageManagerInterface
    {
        return new QueueMessageManager(
            $this->getConfig(),
            $this->createPlainQueueMessageProcessor(),
            $this->createBulkQueueMessageProcessor(),
            $this->createSearchManager()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\QueueMessageManager\QueueMessageManagerInterface
     */
    public function createStorageQueueMessageManager(): QueueMessageManagerInterface
    {
        return new QueueMessageManager(
            $this->getConfig(),
            $this->createPlainQueueMessageProcessor(),
            $this->createBulkQueueMessageProcessor(),
            $this->createStorageManager()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface
     */
    public function createPlainQueueMessageProcessor(): QueueMessageProcessorInterface
    {
        return new QueueMessageProcessor(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\Synchronization\Business\QueueMessageProcessor\QueueMessageProcessorInterface
     */
    public function createBulkQueueMessageProcessor(): QueueMessageProcessorInterface
    {
        return new BulkQueueMessageProcessor(
            $this->getUtilEncodingService()
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
    public function getSearchClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface
     */
    public function getQueueClient()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[]
     */
    public function getSynchronizationDataPlugins()
    {
        return $this->getProvidedDependency(SynchronizationDependencyProvider::PLUGINS_SYNCHRONIZATION_DATA);
    }
}
