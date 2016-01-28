<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Composer\Updater;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class RequireUpdater implements UpdaterInterface
{

    const KEY_REQUIRE = 'require';

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
        $dependentBundles = $this->getDependentBundles($bundleName);

        foreach ($dependentBundles as $dependentBundle) {
            $filter = new CamelCaseToDash();
            $dependentBundle = strtolower($filter->filter($dependentBundle));

            $composerJson[self::KEY_REQUIRE]['spryker/' . $dependentBundle] = '^1.0.0';
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
     *
     * @return array
     */
    private function getDependentBundles($bundleName)
    {
        $dependencyTree = $this->dependencyTreeReader->read();
        $dependentBundles = [];
        foreach ($dependencyTree as $dependency) {
            if ($dependency[DependencyTree::META_BUNDLE] === $bundleName && !in_array($dependency[DependencyTree::META_FOREIGN_BUNDLE], $dependentBundles)) {
                $dependentBundles[] = $dependency[DependencyTree::META_FOREIGN_BUNDLE];
            }
        }
        sort($dependentBundles);

        return $dependentBundles;
    }

}
