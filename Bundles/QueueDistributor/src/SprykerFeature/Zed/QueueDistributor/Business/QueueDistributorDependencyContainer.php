<?php

namespace SprykerFeature\Zed\QueueDistributor\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\QueueDistributorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\QueueDistributor\Business\Builder\QueueNameBuilderInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Distributor\ItemDistributorInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Distributor\QueueDistributor;
use SprykerFeature\Zed\QueueDistributor\Business\Marker\LastDistributionMarkerInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Provider\ItemQueueProviderInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Router\QueueRouterInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Writer\ItemTypeWriterInterface;
use SprykerFeature\Zed\QueueDistributor\Business\Writer\ItemWriterInterface;
use SprykerFeature\Zed\QueueDistributor\Dependency\Facade\QueueDistributorToQueueInterface;
use SprykerFeature\Zed\QueueDistributor\Persistence\QueueDistributorQueryContainerInterface;
use SprykerFeature\Zed\QueueDistributor\QueueDistributorConfig;
use SprykerFeature\Zed\QueueDistributor\QueueDistributorDependencyProvider;

/**
 * @method QueueDistributorConfig getConfig()
 * @method QueueDistributorBusiness getFactory()
 * @method QueueDistributorQueryContainerInterface getQueryContainer()
 */
class QueueDistributorDependencyContainer extends AbstractDependencyContainer
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
     * @return QueueDistributor
     */
    public function createQueueDistributor()
    {
        $queueDistributor = $this->getFactory()->createDistributorQueueDistributor(
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
            $this->createQueueRouter(),
            $this->createItemQueueProvider()
        );

        foreach ($this->getConfig()->getProcessors() as $itemProcessor) {
            $itemDistributor->addProcessor($itemProcessor);
        }

        return $itemDistributor;
    }

    /**
     * @throws \ErrorException
     * @return QueueDistributorToQueueInterface
     */
    protected function getQueueFacade()
    {
        return $this->getProvidedDependency(QueueDistributorDependencyProvider::FACADE_QUEUE);
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
     * @return QueueRouterInterface
     */
    protected function createQueueRouter()
    {
        return $this->getFactory()->createRouterQueueRouter(
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
