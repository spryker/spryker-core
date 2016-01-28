<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class RequireUpdater implements UpdaterInterface
{

    /**
     * @var DependencyTreeReaderInterface
     */
    private $dependencyTreeReader;

    /**
     * @param DependencyTreeReaderInterface $dependencyTreeReader
     */
    public function __construct(DependencyTreeReaderInterface $dependencyTreeReader)
    {
        $this->dependencyTreeReader = $dependencyTreeReader;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    public function update(array $composerJson)
    {
        $bundleName = $this->getBundleName($composerJson);

        $dependencyTree = $this->dependencyTreeReader->read();
        $dependentBundles = $this->getDependentBundles($bundleName, $dependencyTree);

        if (!isset($composerJson['require'])) {
            $composerJson['require'] = [];
        }

        foreach ($dependentBundles as $dependentBundle) {
            $filter = new CamelCaseToDash();
            $dependentBundle = strtolower($filter->filter($dependentBundle));

            $composerJson['require']['spryker/' . $dependentBundle] = '^1.0.0';
        }

        return $composerJson;
    }

    /**
     * @param array $composerJsonData
     *
     * @return string
     */
    private function getBundleName($composerJsonData)
    {
        $nameParts = explode('/', $composerJsonData['name']);
        $bundleName = array_pop($nameParts);
        $filter = new DashToCamelCase();

        return $filter->filter($bundleName);
    }

    /**
     * @param string $bundleName
     * @param array $dependencyTree
     *
     * @return array
     */
    private function getDependentBundles($bundleName, array $dependencyTree)
    {
        $dependentBundles = [];
        foreach ($dependencyTree as $dependency) {
            if ($dependency[DependencyTree::META_BUNDLE] === $bundleName && !in_array($dependency[DependencyTree::META_FOREIGN_BUNDLE], $dependentBundles)) {
                $dependentBundles[] = $dependency[DependencyTree::META_FOREIGN_BUNDLE];
            }
        }

        return $dependentBundles;
    }

}
