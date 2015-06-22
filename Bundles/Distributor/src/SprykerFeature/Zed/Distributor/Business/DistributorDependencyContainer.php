<?php

namespace SprykerFeature\Zed\Distributor\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\DistributorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Distributor\Business\Builder\QueueNameBuilderInterface;
use SprykerFeature\Zed\Distributor\Business\Distributor\ItemDistributorInterface;
use SprykerFeature\Zed\Distributor\Business\Distributor\Distributor;
use SprykerFeature\Zed\Distributor\Business\Marker\LastDistributionMarkerInterface;
use SprykerFeature\Zed\Distributor\Business\Provider\ItemQueueProviderInterface;
use SprykerFeature\Zed\Distributor\Business\Router\MessageRouterInterface;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemTypeWriterInterface;
use SprykerFeature\Zed\Distributor\Business\Writer\ItemWriterInterface;
use SprykerFeature\Zed\Distributor\Dependency\Facade\DistributorToQueueInterface;
use SprykerFeature\Zed\Distributor\Persistence\DistributorQueryContainerInterface;
use SprykerFeature\Zed\Distributor\DistributorConfig;
use SprykerFeature\Zed\Distributor\DistributorDependencyProvider;

/**
 * @method DistributorConfig getConfig()
 * @method DistributorBusiness getFactory()
 * @method DistributorQueryContainerInterface getQueryContainer()
 */
class DistributorDependencyContainer extends AbstractDependencyContainer
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
     * @return Distributor
     */
    public function createQueueDistributor()
    {
        $queueDistributor = $this->getFactory()->createDistributorDistributor(
            $this->getQueryContainer(),
            $this->createLatestDistributionMarker(),
            $this->createItemDistributor()
        );

        foreach ($this->getConfig()->getQueryExpander() as $queryExpander) {
            $queueDistributor->addQueryExpander($queryExpander);
        }

        return $queueDistributor;
    }

    /**
     * @return ItemDistributorInterface
     */
    protected function createItemDistributor()
    {
        $itemDistributor =  $this->getFactory()->createDistributorItemDistributor(
            $this->createMessageRouter(),
            $this->createItemQueueProvider()
        );

        foreach ($this->getConfig()->getItemProcessors() as $itemProcessor) {
            $itemDistributor->addItemProcessor($itemProcessor);
        }

        return $itemDistributor;
    }

    /**
     * @throws \ErrorException
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
}
