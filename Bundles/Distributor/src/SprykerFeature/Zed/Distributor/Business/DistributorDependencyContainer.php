<?php

namespace SprykerFeature\Zed\Distributor\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DistributorBusiness;
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
 * @method DistributorBusiness getFactory()
 * @method DistributorQueryContainerInterface getQueryContainer()
 */
class DistributorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return ItemWriterInterface
     */
    public function createItemWriter()
    {
        return $this->getFactory()->createWriterItemWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return TypeDistributor
     */
    public function createDistributor()
    {
        $queueDistributor = $this->getFactory()->createDistributorTypeDistributor(
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
        return $this->getFactory()->createInternalItemTypeInstaller(
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
        return $this->getFactory()->createInternalReceiverInstaller(
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
        $itemDistributor = $this->getFactory()->createDistributorItemDistributor(
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
        return $this->getFactory()->createMarkerLastDistributionMarker(
            $this->getQueryContainer(),
            $this->createItemTypeWriter()
        );
    }

    /**
     * @return ItemTypeWriterInterface
     */
    protected function createItemTypeWriter()
    {
        return $this->getFactory()->createWriterItemTypeWriter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return ReceiverWriterInterface
     */
    protected function createReceiverWriter()
    {
        return $this->getFactory()->createWriterReceiverWriter();
    }

    /**
     * @return MessageRouterInterface
     */
    protected function createMessageRouter()
    {
        return $this->getFactory()->createRouterMessageRouter(
            $this->getQueueFacade()
        );
    }

    /**
     * @return ItemQueueProviderInterface
     */
    protected function createItemQueueProvider()
    {
        return $this->getFactory()->createProviderItemQueueProvider(
            $this->getQueryContainer(),
            $this->createQueueNameBuilder()
        );
    }

    /**
     * @return QueueNameBuilderInterface
     */
    protected function createQueueNameBuilder()
    {
        return $this->getFactory()->createBuilderQueueNameBuilder();
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
