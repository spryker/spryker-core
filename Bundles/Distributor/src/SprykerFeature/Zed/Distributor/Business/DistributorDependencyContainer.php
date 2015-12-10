<?php

namespace SprykerFeature\Zed\Distributor\Business;

use SprykerFeature\Zed\Distributor\Business\Builder\QueueNameBuilder;
use SprykerFeature\Zed\Distributor\Business\Provider\ItemQueueProvider;
use SprykerFeature\Zed\Distributor\Business\Router\MessageRouter;
use SprykerFeature\Zed\Distributor\Business\Writer\ReceiverWriter;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemTypeWriter;
use SprykerFeature\Zed\Distributor\Business\Marker\LastDistributionMarker;
use SprykerFeature\Zed\Distributor\Business\Distributor\ItemDistributor;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemWriter;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Distributor\Business\Builder\QueueNameBuilderInterface;
use SprykerFeature\Zed\Distributor\Business\Distributor\ItemDistributorInterface;
use SprykerFeature\Zed\Distributor\Business\Distributor\TypeDistributor;
use SprykerFeature\Zed\Distributor\Business\Internal\ItemTypeInstaller;
use SprykerFeature\Zed\Distributor\Business\Internal\ReceiverInstaller;
use SprykerFeature\Zed\Distributor\Business\Marker\LastDistributionMarkerInterface;
use SprykerFeature\Zed\Distributor\Business\Provider\ItemQueueProviderInterface;
use SprykerFeature\Zed\Distributor\Business\Router\MessageRouterInterface;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemTypeWriterInterface;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemWriterInterface;
use SprykerFeature\Zed\Distributor\Business\Writer\ReceiverWriterInterface;
use SprykerFeature\Zed\Distributor\Dependency\Facade\DistributorToQueueInterface;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\ItemProcessorPluginInterface;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\DistributorQueryExpanderPluginInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use SprykerFeature\Zed\Distributor\DistributorConfig;
use SprykerFeature\Zed\Distributor\DistributorDependencyProvider;

/**
 * @method DistributorConfig getConfig()
 * @method DistributorQueryContainerInterface getQueryContainer()
 */
class DistributorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ItemWriterInterface
     */
    public function createItemWriter()
    {
        return new ItemWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return TypeDistributor
     */
    public function createDistributor()
    {
        $queueDistributor = new TypeDistributor(
            $this->getQueryContainer(),
            $this->createLatestDistributionMarker(),
            $this->createItemDistributor()
        );

        foreach ($this->getQueryExpanders() as $queryExpander) {
            $queueDistributor->addQueryExpander($queryExpander);
        }

        return $queueDistributor;
    }

    /**
     * @return ItemTypeInstaller
     */
    public function createItemTypeInstaller()
    {
        return new ItemTypeInstaller(
            $this->getConfig()->getItemTypes(),
            $this->createItemTypeWriter(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return ReceiverInstaller
     */
    public function createReceiverInstaller()
    {
        return new ReceiverInstaller(
            $this->getConfig()->getItemReceivers(),
            $this->createReceiverWriter(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return ItemDistributorInterface
     */
    protected function createItemDistributor()
    {
        $itemDistributor = new ItemDistributor(
            $this->createMessageRouter(),
            $this->createItemQueueProvider()
        );

        foreach ($this->getItemProcessors() as $itemProcessor) {
            $itemDistributor->addItemProcessor($itemProcessor);
        }

        return $itemDistributor;
    }

    /**
     * @throws \ErrorException
     *
     * @return DistributorToQueueInterface
     */
    protected function getQueueFacade()
    {
        return $this->getProvidedDependency(DistributorDependencyProvider::FACADE_QUEUE);
    }

    /**
     * @return LastDistributionMarkerInterface
     */
    protected function createLatestDistributionMarker()
    {
        return new LastDistributionMarker(
            $this->getQueryContainer(),
            $this->createItemTypeWriter()
        );
    }

    /**
     * @return ItemTypeWriterInterface
     */
    protected function createItemTypeWriter()
    {
        return new ItemTypeWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ReceiverWriterInterface
     */
    protected function createReceiverWriter()
    {
        return new ReceiverWriter();
    }

    /**
     * @return MessageRouterInterface
     */
    protected function createMessageRouter()
    {
        return new MessageRouter(
            $this->getQueueFacade()
        );
    }

    /**
     * @return ItemQueueProviderInterface
     */
    protected function createItemQueueProvider()
    {
        return new ItemQueueProvider(
            $this->getQueryContainer(),
            $this->createQueueNameBuilder()
        );
    }

    /**
     * @return QueueNameBuilderInterface
     */
    protected function createQueueNameBuilder()
    {
        return new QueueNameBuilder();
    }

    /**
     * @return ItemProcessorPluginInterface[]
     */
    protected function getItemProcessors()
    {
        return $this->getProvidedDependency(DistributorDependencyProvider::ITEM_PROCESSORS);
    }

    /**
     * @return DistributorQueryExpanderPluginInterface[]
     */
    protected function getQueryExpanders()
    {
        return $this->getProvidedDependency(DistributorDependencyProvider::QUERY_EXPANDERS);
    }

}
