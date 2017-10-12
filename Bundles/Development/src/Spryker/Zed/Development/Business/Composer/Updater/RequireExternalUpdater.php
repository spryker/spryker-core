<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Development\DevelopmentConstants;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class RequireExternalUpdater implements UpdaterInterface
{
    const KEY_REQUIRE = 'require';
    const RELEASE_OPERATOR = '^';
    const KEY_NAME = 'name';

    /**
     * @var array
     */
    protected $externalDependencyTree;

    /**
     * @var array
     */
    protected $externalToInternalMap;

    /**
     * @var array
     */
    protected $ignorableDependencies;

    /**
     * @param array $externalDependencyTree
     * @param array $externalToInternalMap
     * @param array $ignorableDependencies
     */
    public function __construct(array $externalDependencyTree, array $externalToInternalMap, array $ignorableDependencies)
    {
        $this->externalDependencyTree = $externalDependencyTree;
        $this->externalToInternalMap = $externalToInternalMap;
        $this->ignorableDependencies = $ignorableDependencies;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $bundleName = $this->getBundleName($composerJson);

        $dependentBundles = $this->getExternalBundles($bundleName);

        if (!Config::hasValue(DevelopmentConstants::COMPOSER_REQUIRE_VERSION_EXTERNAL)) {
            return $composerJson;
        }
        $composerRequireVersion = Config::get(DevelopmentConstants::COMPOSER_REQUIRE_VERSION_EXTERNAL);

        if (preg_match('/^[0-9]/', $composerRequireVersion)) {
            $composerRequireVersion = static::RELEASE_OPERATOR . $composerRequireVersion;
        }

        foreach ($dependentBundles as $dependentBundle) {
            if (empty($dependentBundle) || $dependentBundle === $composerJson[static::KEY_NAME]) {
                continue;
            }
            $filter = new CamelCaseToDash();
            $dependentBundle = strtolower($filter->filter($dependentBundle));

            $composerJson[static::KEY_REQUIRE][$dependentBundle] = static::RELEASE_OPERATOR . $composerRequireVersion;
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
        $nameParts = explode('/', $composerJsonData[static::KEY_NAME]);
        $bundleName = array_pop($nameParts);
        $filter = new DashToCamelCase();

        return $filter->filter($bundleName);
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function getExternalBundles($bundleName)
    {
        $dependentBundles = [];
        foreach ($this->externalDependencyTree as $dependency) {
            if ($dependency[DependencyTree::META_BUNDLE] === $bundleName
                && !in_array($dependency[DependencyTree::META_COMPOSER_NAME], $this->ignorableDependencies)
            ) {
                $dependentBundles[] = $this->mapExternalToInternal($dependency[DependencyTree::META_COMPOSER_NAME]);
            }
        }
        $dependentBundles = array_unique($dependentBundles);
        sort($dependentBundles);

        return $dependentBundles;
    }

    /**
     * @param string $composerName
     *
     * @return string
     */
    protected function mapExternalToInternal($composerName)
    {
        foreach ($this->externalToInternalMap as $external => $internal) {
            if ($external[0] === '/') {
                if (preg_match($external, $composerName)) {
                    return $internal;
                }
            } elseif ($external === $composerName) {
                return $internal;
            }
        }
    }
}
