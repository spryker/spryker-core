<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinder;
use Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface;
use Spryker\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinder;
use Spryker\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinderInterface;
use Spryker\Zed\SprykGui\Business\Finder\Module\ModuleFinder;
use Spryker\Zed\SprykGui\Business\Finder\Module\ModuleFinderInterface;
use Spryker\Zed\SprykGui\Business\Model\Graph\GraphBuilder;
use Spryker\Zed\SprykGui\Business\Model\Graph\GraphBuilderInterface;
use Spryker\Zed\SprykGui\Business\Model\Spryk;
use Spryker\Zed\SprykGui\Business\Model\SprykInterface;
use Spryker\Zed\SprykGui\Dependency\Client\SprykGuiToSessionClientInterface;
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;
use Spryker\Zed\SprykGui\SprykGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiQueryContainerInterface getQueryContainer()
 */
class SprykGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SprykGui\Business\Model\SprykInterface
     */
    public function createSpryk(): SprykInterface
    {
        return new Spryk(
            $this->getSprykFacade(),
            $this->createGraphBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Model\Graph\GraphBuilderInterface
     */
    public function createGraphBuilder(): GraphBuilderInterface
    {
        return new GraphBuilder(
            $this->getSprykFacade(),
            $this->getGraphPlugin()
        );
    }

    /**
     * @return \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface
     */
    public function getSprykFacade(): SprykGuiToSprykFacadeInterface
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::SPRYK_FACADE);
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    public function getGraphPlugin(): GraphPlugin
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Finder\Module\ModuleFinderInterface
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        return new ModuleFinder($this->getSessionClient());
    }

    /**
     * @return \Spryker\Zed\SprykGui\Dependency\Client\SprykGuiToSessionClientInterface
     */
    public function getSessionClient(): SprykGuiToSessionClientInterface
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface
     */
    public function createAccessibleTransferFinder(): AccessibleTransferFinderInterface
    {
        return new AccessibleTransferFinder();
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinderInterface
     */
    public function createFactoryInformationFinder(): FactoryInfoFinderInterface
    {
        return new FactoryInfoFinder();
    }
}
