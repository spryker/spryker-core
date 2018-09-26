<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderComposite;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderCompositeInterface;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\Zed\Business\Model\ZedBusinessModelChoiceLoader;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\Zed\Communication\Controller\ZedCommunicationControllerChoiceLoader;
use Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinder;
use Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface;
use Spryker\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinder;
use Spryker\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinderInterface;
use Spryker\Zed\SprykGui\Business\Finder\Module\ModuleFinder;
use Spryker\Zed\SprykGui\Business\Finder\Module\ModuleFinderInterface;
use Spryker\Zed\SprykGui\Business\Finder\Organization\OrganizationFinder;
use Spryker\Zed\SprykGui\Business\Finder\Organization\OrganizationFinderInterface;
use Spryker\Zed\SprykGui\Business\Graph\GraphBuilder;
use Spryker\Zed\SprykGui\Business\Graph\GraphBuilderInterface;
use Spryker\Zed\SprykGui\Business\Option\Argument\ArgumentOptionBuilder;
use Spryker\Zed\SprykGui\Business\Option\ClassName\ClassNameOptionBuilder;
use Spryker\Zed\SprykGui\Business\Option\OptionBuilder;
use Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface;
use Spryker\Zed\SprykGui\Business\Option\Output\ModuleOutputOptionBuilder;
use Spryker\Zed\SprykGui\Business\PhpInternal\Type\Type;
use Spryker\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface;
use Spryker\Zed\SprykGui\Business\Spryk\Spryk;
use Spryker\Zed\SprykGui\Business\Spryk\SprykInterface;
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;
use Spryker\Zed\SprykGui\SprykGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiBusinessFactory getFactory()
 */
class SprykGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SprykGui\Business\Spryk\SprykInterface
     */
    public function createSpryk(): SprykInterface
    {
        return new Spryk(
            $this->getSprykFacade(),
            $this->createGraphBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Graph\GraphBuilderInterface
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
        return new ModuleFinder();
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Finder\Organization\OrganizationFinderInterface
     */
    public function createOrganizationFinder(): OrganizationFinderInterface
    {
        return new OrganizationFinder();
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

    /**
     * @return \Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createOptionBuilder(): OptionBuilderInterface
    {
        return new OptionBuilder([
            $this->createClassNameOptionBuilder(),
            $this->createOutputOptionBuilder(),
            $this->createArgumentOptionBuilder(),
        ]);
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createClassNameOptionBuilder(): OptionBuilderInterface
    {
        return new ClassNameOptionBuilder();
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createOutputOptionBuilder(): OptionBuilderInterface
    {
        return new ModuleOutputOptionBuilder();
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createArgumentOptionBuilder(): OptionBuilderInterface
    {
        return new ArgumentOptionBuilder(
            $this->createAccessibleTransferFinder(),
            $this->createPhpInternalTypes()
        );
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface
     */
    public function createPhpInternalTypes(): TypeInterface
    {
        return new Type();
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderCompositeInterface
     */
    public function createChoiceLoader(): ChoiceLoaderCompositeInterface
    {
        return new ChoiceLoaderComposite([
            $this->createZedBusinessModelLoader(),
            $this->createZedControllerChoiceLoader(),
        ]);
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createZedBusinessModelLoader(): ChoiceLoaderInterface
    {
        return new ZedBusinessModelChoiceLoader();
    }

    /**
     * @return \Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createZedControllerChoiceLoader(): ChoiceLoaderInterface
    {
        return new ZedCommunicationControllerChoiceLoader();
    }
}
