<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Stability;

use ArrayObject;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\ClassNameFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\InTestDependencyFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class StabilityCalculator implements StabilityCalculatorInterface
{
    /**
     * @var array
     */
    protected $bundles = [];

    /**
     * @var array
     */
    protected $bundlesDependencies;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface
     */
    protected $filter;

    public function __construct()
    {
        $filter = new TreeFilter();
        $filter->addFilter(new ClassNameFilter('/\\Dependency\\\(.*?)Interface/'))
            ->addFilter(new InTestDependencyFilter());

        $this->filter = $filter;
    }

    /**
     * @return array
     */
    public function calculateStability()
    {
        $bundlesDependencies = json_decode(file_get_contents(APPLICATION_ROOT_DIR . '/data/dependencyTree.json'), true);

        $this->bundlesDependencies = $this->filter($bundlesDependencies);

        foreach ($this->bundlesDependencies as $bundlesDependency) {
            if ($bundlesDependency[DependencyTree::META_IS_OPTIONAL] || $bundlesDependency[DependencyTree::META_IN_TEST]) {
                continue;
            }
            $currentBundleName = $bundlesDependency['bundle'];
            $outgoingBundleName = $bundlesDependency['foreign bundle'];

            if (!isset($this->bundles[$currentBundleName])) {
                $this->addInfoStack($currentBundleName);
            }
            if (!isset($this->bundles[$outgoingBundleName])) {
                $this->addInfoStack($outgoingBundleName);
            }

            $this->bundles[$currentBundleName]['out'][$outgoingBundleName] = $outgoingBundleName;
            $this->bundles[$outgoingBundleName]['in'][$currentBundleName] = $currentBundleName;
        }

        ksort($this->bundles);

        $this->calculateBundlesStability();
        $this->calculateIndirectBundlesStability();
        $this->calculateSprykerStability();

        return $this->bundles;
    }

    /**
     * @param array $bundlesDependencies
     *
     * @return array
     */
    protected function filter(array $bundlesDependencies)
    {
        $callback = function (array $bundleDependency) {
            return ($bundleDependency[DependencyTree::META_FOREIGN_BUNDLE] !== 'external');
        };
        $bundlesDependencies = array_filter($bundlesDependencies, $callback);
        $bundlesDependencies = $this->filter->filter($bundlesDependencies);

        return $bundlesDependencies;
    }

    /**
     * @param string $bundle
     *
     * @return void
     */
    protected function addInfoStack($bundle)
    {
        $this->bundles[$bundle] = [
            'in' => [],
            'indirectIn' => [],
            'out' => [],
            'indirectOut' => [],
            'stability' => 0,
            'indirectStability' => 0,
            'sprykerStability' => 0,
        ];
    }

    /**
     * @return void
     */
    protected function calculateBundlesStability()
    {
        foreach ($this->bundles as &$bundle) {
            $stability = count($bundle['out']) / (count($bundle['in']) + count($bundle['out']));
            $bundle['stability'] = number_format($stability, 3);
        }
    }

    /**
     * @return void
     */
    protected function calculateIndirectBundlesStability()
    {
        foreach ($this->bundles as $bundle => $info) {
            $indirectOutgoingDependencies = new ArrayObject();
            $this->buildIndirectOutgoingDependencies($bundle, $indirectOutgoingDependencies);
            $this->bundles[$bundle]['indirectOut'] = $indirectOutgoingDependencies->getArrayCopy();

            $indirectIncomingDependencies = new ArrayObject();
            $incomingBundles = $this->bundles[$bundle]['in'];
            $this->buildIndirectIncomingDependencies($bundle, $indirectIncomingDependencies);

            $indirectIncomingDependencies = $indirectIncomingDependencies->getArrayCopy();
            $callback = function ($bundle) use ($incomingBundles) {
                return !in_array($bundle, $incomingBundles);
            };
            $indirectIncomingDependencies = array_filter($indirectIncomingDependencies, $callback);
            $this->bundles[$bundle]['indirectIn'] = $indirectIncomingDependencies;
            $divisor = (count($this->bundles[$bundle]['indirectIn']) + count($this->bundles[$bundle]['indirectOut']));
            $indirectStability = ($divisor > 0) ? count($this->bundles[$bundle]['indirectOut']) / $divisor : 0;

            $this->bundles[$bundle]['indirectStability'] = number_format($indirectStability, 3);
        }
    }

    /**
     * @return void
     */
    protected function calculateSprykerStability()
    {
        foreach ($this->bundles as $bundle => $info) {
            $sprykerStability = (count($info['indirectIn']) * count($info['indirectOut'])) * (1 - abs(0.5 - $info['indirectStability']));
            $this->bundles[$bundle]['sprykerStability'] = number_format($sprykerStability, 3);
        }
    }

    /**
     * @param string $bundleName
     * @param \ArrayObject $indirectOutgoingDependencies
     *
     * @return void
     */
    protected function buildIndirectOutgoingDependencies($bundleName, ArrayObject $indirectOutgoingDependencies)
    {
        $dependencies = $this->bundles[$bundleName]['out'];

        foreach ($dependencies as $dependentBundle) {
            if ($indirectOutgoingDependencies->offsetExists($dependentBundle)) {
                continue;
            }
            $indirectOutgoingDependencies[$dependentBundle] = $dependentBundle;
            $this->buildIndirectOutgoingDependencies($dependentBundle, $indirectOutgoingDependencies);
        }
    }

    /**
     * @param string $bundleName
     * @param \ArrayObject $indirectIncomingDependencies
     *
     * @return void
     */
    protected function buildIndirectIncomingDependencies($bundleName, ArrayObject $indirectIncomingDependencies)
    {
        $dependencies = $this->bundles[$bundleName]['in'];

        foreach ($dependencies as $dependentBundle) {
            if ($indirectIncomingDependencies->offsetExists($dependentBundle)) {
                continue;
            }
            $indirectIncomingDependencies[$dependentBundle] = $dependentBundle;
            $this->buildIndirectIncomingDependencies($dependentBundle, $indirectIncomingDependencies);
        }
    }
}
