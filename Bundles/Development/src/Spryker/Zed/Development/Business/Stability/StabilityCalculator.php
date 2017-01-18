<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Stability;

use ArrayObject;

class StabilityCalculator implements StabilityCalculatorInterface
{

    /**
     * @var array
     */
    protected $bundles = [];

    /**
     * @return array
     */
    public function calculateStability()
    {
        $bundlesDependencies = json_decode(file_get_contents(APPLICATION_ROOT_DIR . '/data/dependencyTree.json'), true);

        foreach ($bundlesDependencies as $bundlesDependency) {
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

        $this->calculateBundlesStability();
        $this->calculateIndirectBundlesStability();

        ksort($this->bundles);

        return $this->bundles;
    }

    /**
     * @param $bundle
     *
     * @return void
     */
    protected function addInfoStack($bundle)
    {
        $this->bundles[$bundle] = [
            'in' => [],
            'out' => [],
            'indirectOut' => [],
            'stability' => 0,
            'indirectStability' => 0,
        ];
    }

    /**
     * @return void
     */
    protected function calculateBundlesStability()
    {
        foreach ($this->bundles as &$bundle) {
            $stability = count($bundle['out']) / (count($bundle['in']) + count($bundle['out']));
            $bundle['stability'] = $stability;
        }
    }

    /**
     * @return void
     */
    protected function calculateIndirectBundlesStability()
    {
        foreach ($this->bundles as $bundle => $info) {
            $allDependencies = new ArrayObject();
            $this->buildDependencyTree($bundle, $allDependencies);
            $this->bundles[$bundle]['indirectOut'] = $allDependencies->getArrayCopy();

            $indirectStability = count($this->bundles[$bundle]['indirectOut']) / (count($this->bundles[$bundle]['in']) + count($this->bundles[$bundle]['indirectOut']));
            $this->bundles[$bundle]['indirectStability'] = $indirectStability;
        }
    }

    /**
     * @param string $bundleName
     * @param \ArrayObject $allDependencies
     *
     * @return void
     */
    protected function buildDependencyTree($bundleName, ArrayObject $allDependencies)
    {
        $dependencies = $this->bundles[$bundleName]['out'];

        $allDependencies[$bundleName] = $dependencies;
        foreach ($dependencies as $dependentBundle) {
            if (array_key_exists($dependentBundle, $allDependencies)) {
                continue;
            }
            $this->buildDependencyTree($dependentBundle, $allDependencies);
        }
    }

    /**
     * @param $bundle
     * @param array $out
     *
     * @return mixed
     */
    protected function sumIndirectOutgoingDependencies($bundle, array $out)
    {
        $currentBundleInfo = $this->bundles[$bundle];
        $outgoingBundles = $currentBundleInfo['out'];
        foreach ($outgoingBundles as $outgoingBundle) {
            if (!in_array($outgoingBundle, $out)) {
                $out = array_merge($out, $this->sumIndirectOutgoingDependencies($outgoingBundle, $out));
            }
        }

        return $out;
    }
}
