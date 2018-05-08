<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Development\DevelopmentConstants;
use Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class RequireUpdater implements UpdaterInterface
{
    const KEY_REQUIRE = 'require';
    const KEY_REQUIRE_PHP = 'php';
    const PHP_MINIMUM = '>=7.1';
    const RELEASE_OPERATOR = '^';
    const EXTERNAL_DEPENDENCIES_BUNDLE_NAME = 'External';

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface
     */
    protected $dependencyTreeReader;

    /**
     * @var \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface
     */
    protected $treeFilter;

    /**
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface $dependencyTreeReader
     * @param \Spryker\Zed\Development\Business\DependencyTree\DependencyFilter\TreeFilterInterface $treeFilter
     */
    public function __construct(DependencyTreeReaderInterface $dependencyTreeReader, TreeFilterInterface $treeFilter)
    {
        $this->dependencyTreeReader = $dependencyTreeReader;
        $this->treeFilter = $treeFilter;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson = $this->requirePhpVersion($composerJson);

        $bundleName = $this->getBundleName($composerJson);
        $dependentBundles = $this->getDependentBundles($bundleName);

        if (!Config::hasValue(DevelopmentConstants::COMPOSER_REQUIRE_VERSION)) {
            return $composerJson;
        }
        $composerRequireVersion = Config::get(DevelopmentConstants::COMPOSER_REQUIRE_VERSION);

        if (preg_match('/^[0-9]/', $composerRequireVersion)) {
            $composerRequireVersion = static::RELEASE_OPERATOR . $composerRequireVersion;
        }

        foreach ($dependentBundles as $dependentBundle) {
            $filter = new CamelCaseToDash();
            $dependentBundle = strtolower($filter->filter($dependentBundle));
            $composerJson[static::KEY_REQUIRE]['spryker/' . $dependentBundle] = $composerRequireVersion;
        }

        return $composerJson;
    }

    /**
     * @param array $composerJsonData
     *
     * @return string
     */
    protected function getBundleName(array $composerJsonData)
    {
        $nameParts = explode('/', $composerJsonData['name']);
        $bundleName = array_pop($nameParts);
        $filter = new DashToCamelCase();

        return (string)$filter->filter($bundleName);
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function getDependentBundles($bundleName)
    {
        $dependencyTree = $this->treeFilter->filter($this->dependencyTreeReader->read());
        $dependentBundles = [];
        foreach ($dependencyTree as $dependency) {
            if ($dependency[DependencyTree::META_FOREIGN_BUNDLE] === 'external') {
                continue;
            }
            if ($dependency[DependencyTree::META_MODULE] === $bundleName) {
                $dependentBundles[] = $dependency[DependencyTree::META_FOREIGN_BUNDLE];
            }
        }
        $dependentBundles = array_unique($dependentBundles);
        sort($dependentBundles);

        return $dependentBundles;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function requirePhpVersion(array $composerJson)
    {
        if (isset($composerJson[static::KEY_REQUIRE][static::KEY_REQUIRE_PHP])) {
            return $composerJson;
        }

        $composerJson[static::KEY_REQUIRE][static::KEY_REQUIRE_PHP] = static::PHP_MINIMUM;

        return $composerJson;
    }
}
