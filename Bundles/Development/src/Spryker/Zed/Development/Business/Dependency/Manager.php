<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Symfony\Component\Finder\Finder;

class Manager
{

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\BundleParser
     */
    protected $bundleParser;

    /**
     * @var string
     */
    protected $bundleDirectory;

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\BundleParser $bundleParser
     * @param string $bundleDirectory
     */
    public function __construct(BundleParser $bundleParser, $bundleDirectory)
    {
        $this->bundleParser = $bundleParser;
        $this->bundleDirectory = $bundleDirectory;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function parseIncomingDependencies($bundleName)
    {
        $allForeignBundles = $this->collectAllForeignBundles($bundleName);

        $incomingDependencies = [];
        foreach ($allForeignBundles as $foreignBundle) {
            try {
                $dependencies = $this->bundleParser->parseOutgoingDependencies($foreignBundle);
            } catch (\Throwable $e) {
                $dependencies = []; // TODO illegal try-catch
            } catch (\Exception $e) {
                $dependencies = []; // TODO illegal try-catch
            }
            if (array_key_exists($bundleName, $dependencies)) {
                if (array_key_exists($foreignBundle, $incomingDependencies) === false) {
                    $incomingDependencies[$foreignBundle] = 0;
                }
                $incomingDependencies[$foreignBundle] += $dependencies[$bundleName];
            }
        }

        return $incomingDependencies;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function collectAllForeignBundles($bundleName)
    {
        $bundles = $this->collectCoreBundles();
        $allForeignBundles = [];

        foreach ($bundles as $bundle) {
            $foreignBundleName = $bundle->getFilename();
            if ($foreignBundleName !== $bundleName) {
                $allForeignBundles[] = $foreignBundleName;
            }
        }
        asort($allForeignBundles);

        return $allForeignBundles;
    }

    /**
     * @return array
     */
    public function collectAllBundles()
    {
        $bundles = $this->collectCoreBundles();
        $allBundles = [];

        foreach ($bundles as $bundle) {
            $allBundles[] = $bundle->getFilename();
        }
        asort($allBundles);

        return $allBundles;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function collectCoreBundles()
    {
        $bundles = (new Finder())->directories()->depth('== 0')->in($this->bundleDirectory);

        return $bundles;
    }

}
