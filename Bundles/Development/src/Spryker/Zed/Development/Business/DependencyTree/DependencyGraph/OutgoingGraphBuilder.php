<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyGraph;

use ArrayObject;
use Generated\Shared\Transfer\BundleDependencyCollectionTransfer;
use Spryker\Zed\Development\Business\Dependency\BundleParserInterface;
use Spryker\Zed\Development\Business\Dependency\Manager;
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
     * @var \Spryker\Zed\Development\Business\Dependency\BundleParserInterface
     */
    protected $bundleParser;

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\Manager
     */
    protected $dependencyManager;

    /**
     * @var array
     */
    protected $bundlesToFilter;

    /**
     * @param string $bundleName
     * @param \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin $graph
     * @param \Spryker\Zed\Development\Business\Dependency\BundleParserInterface $bundleParser
     * @param \Spryker\Zed\Development\Business\Dependency\Manager $dependencyManager
     * @param array $bundlesToFilter
     */
    public function __construct($bundleName, GraphPlugin $graph, BundleParserInterface $bundleParser, Manager $dependencyManager, array $bundlesToFilter = [])
    {
        $this->bundleName = $bundleName;
        $this->graph = $graph;
        $this->bundleParser = $bundleParser;
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
     * @param string $bundleName
     * @param \ArrayObject $allDependencies
     *
     * @return void
     */
    protected function buildGraph($bundleName, ArrayObject $allDependencies)
    {
        $dependencies = $this->bundleParser->parseOutgoingDependencies($bundleName);
        $dependencies = $this->getBundleNames($dependencies);

        if ($bundleName === $this->bundleName) {
            $dependencies = $this->filterBundles($dependencies);
        }

        $allDependencies[$bundleName] = $dependencies;
        foreach ($dependencies as $dependentBundle) {
            if (array_key_exists($dependentBundle, $allDependencies)) {
                continue;
            }
            $this->buildGraph($dependentBundle, $allDependencies);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer
     *
     * @return array
     */
    protected function getBundleNames(BundleDependencyCollectionTransfer $bundleDependencyCollectionTransfer)
    {
        $bundleNames = [];
        foreach ($bundleDependencyCollectionTransfer->getDependencyBundles() as $dependencyBundleTransfer) {
            $hasDependencyInSource = false;

            foreach ($dependencyBundleTransfer->getDependencies() as $dependencyTransfer) {
                if (!$dependencyTransfer->getIsInTest() && !$dependencyTransfer->getIsOptional()) {
                    $hasDependencyInSource = true;
                }
            }

            if ($hasDependencyInSource) {
                $bundleNames[] = $dependencyBundleTransfer->getBundle();
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
