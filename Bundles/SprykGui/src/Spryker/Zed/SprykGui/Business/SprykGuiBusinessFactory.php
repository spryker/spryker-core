<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SprykGui\Business\Model\Graph\GraphBuilder;
use Spryker\Zed\SprykGui\Business\Model\Graph\GraphBuilderInterface;
use Spryker\Zed\SprykGui\Business\Model\Spryk;
use Spryker\Zed\SprykGui\Business\Model\SprykInterface;
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
}
