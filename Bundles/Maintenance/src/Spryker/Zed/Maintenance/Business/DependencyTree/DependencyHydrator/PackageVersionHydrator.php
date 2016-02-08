<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyHydrator;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class PackageVersionHydrator implements DependencyHydratorInterface
{

    /**
     * @var array
     */
    private $installedPackages;

    /**
     * @param array $installedPackages
     */
    public function __construct(array $installedPackages)
    {
        $this->installedPackages = $installedPackages;
    }

    /**
     * @param array $dependency
     *
     * @return void
     */
    public function hydrate(array &$dependency)
    {
        $dependency['composer version'] = $this->getComposerVersion($dependency);
    }

    /**
     * @param array $dependency
     *
     * @return bool|string
     */
    private function getComposerVersion(array $dependency)
    {
        if ($dependency['composer name'] === null || $dependency['composer name'] === false) {
            return false;
        }

        foreach ($this->installedPackages as $installedPackage) {
            if ($installedPackage['name'] === $dependency['composer name']) {
                return $installedPackage['version'];
            }
        }

        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($dependency) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }
}
