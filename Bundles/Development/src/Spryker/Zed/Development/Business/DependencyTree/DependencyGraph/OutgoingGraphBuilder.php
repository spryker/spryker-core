<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyGraph;

use ArrayObject;
use Generated\Shared\Transfer\DependencyCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\Business\Dependency\ManagerInterface;
use Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface;
use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;

class OutgoingGraphBuilder
{
    /**
     * @var string
     */
    protected $bundleName;

    /**
     * @var \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected $graph;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface
     */
    protected $moduleDependencyParser;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\ManagerInterface
     */
    protected $dependencyManager;

    /**
     * @var array
     */
    protected $bundlesToFilter;

    /**
     * @param string $bundleName
     * @param \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin $graph
     * @param \Spryker\Zed\Development\Business\Dependency\ModuleDependencyParserInterface $moduleDependencyParser
     * @param \Spryker\Zed\Development\Business\Dependency\ManagerInterface $dependencyManager
     * @param array $bundlesToFilter
     */
    public function __construct($bundleName, GraphPlugin $graph, ModuleDependencyParserInterface $moduleDependencyParser, ManagerInterface $dependencyManager, array $bundlesToFilter = [])
    {
        $this->bundleName = $bundleName;
        $this->graph = $graph;
        $this->moduleDependencyParser = $moduleDependencyParser;
        $this->dependencyManager = $dependencyManager;
        $this->bundlesToFilter = $bundlesToFilter;
    }

    /**
     * @param bool $showIncomingDependencies
     *
     * @return string
     */
    public function build($showIncomingDependencies = false)
    {
        $this->graph->init('Outgoing dependencies', ['bgcolor' => '#f3f3f4']);

        $allDependencies = new ArrayObject();
        $this->buildGraph($this->bundleName, $allDependencies);

        foreach ($allDependencies as $bundleName => $dependentBundles) {
            $attributes = [
                'label' => $bundleName . '<br /><font point-size="10">' . count($dependentBundles) . '</font>',
                'url' => '/development/dependency/outgoing-graph?bundle=' . $bundleName,
            ];

            if ($this->bundleName === $bundleName) {
                $attributes['fillcolor'] = '#ffffff';
                $attributes['style'] = 'filled';
                $attributes['label'] = $attributes['label'] . '<br /><font color="violet" point-size="13">' . (count($allDependencies) - 1) . ' (indirect)</font>';
            }

            $this->graph->addNode($bundleName, $attributes);
        }

        foreach ($allDependencies as $bundleName => $dependentBundles) {
            foreach ($dependentBundles as $dependentBundle) {
                if ($bundleName !== $dependentBundle) {
                    $this->graph->addEdge($bundleName, $dependentBundle);
                }
            }
        }

        if ($showIncomingDependencies) {
            $this->addIncomingDependencies();
        }

        return $this->graph->render('svg');
    }

    /**
     * @return void
     */
    protected function addIncomingDependencies()
    {
        $incomingDependencies = array_keys($this->dependencyManager->parseIncomingDependencies($this->bundleName));

        foreach ($incomingDependencies as $incomingBundle) {
            $attributes = [
                'url' => '/development/dependency/outgoing-graph?bundle=' . $incomingBundle,
            ];
            $this->graph->addNode($incomingBundle, $attributes);
            $this->graph->addEdge($incomingBundle, $this->bundleName);
        }
    }

    /**
     * @param string $moduleName
     * @param \ArrayObject $allDependencies
     *
     * @return void
     */
    protected function buildGraph($moduleName, ArrayObject $allDependencies)
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName('Spryker');
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setOrganization($organizationTransfer);

        $dependencies = $this->moduleDependencyParser->parseOutgoingDependencies($moduleTransfer);
        $dependencies = $this->getBundleNames($dependencies);

        if ($moduleName === $this->bundleName) {
            $dependencies = $this->filterBundles($dependencies);
        }

        $allDependencies[$moduleName] = $dependencies;
        foreach ($dependencies as $dependentBundle) {
            if ($allDependencies->offsetExists($dependentBundle)) {
                continue;
            }
            $this->buildGraph($dependentBundle, $allDependencies);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleNames(DependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleNames = [];
        foreach ($bundleDependencyCollectionTransfer->getDependencyModules() as $dependencyBundleTransfer) {
            $hasDependencyInSource = false;

            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest() && !$dependencyTransfer->getIsOptional()) {
                    $hasDependencyInSource = true;
                }
            }

            if ($hasDependencyInSource) {
                $bundleNames[] = $dependencyBundleTransfer->getModule();
            }
        }

        return $bundleNames;
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    protected function filterBundles(array $dependencies)
    {
        $callback = function ($bundle) {
            return !in_array($bundle, $this->bundlesToFilter);
        };

        return array_filter($dependencies, $callback);
    }
}
