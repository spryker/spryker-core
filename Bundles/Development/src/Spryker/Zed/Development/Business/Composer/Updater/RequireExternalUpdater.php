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
    public const KEY_REQUIRE = 'require';
    public const RELEASE_OPERATOR = '^';
    public const KEY_NAME = 'name';

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
        $moduleName = $this->getModuleName($composerJson);

        $dependentModules = $this->getExternalModules($moduleName);

        if (!Config::hasValue(DevelopmentConstants::COMPOSER_REQUIRE_VERSION_EXTERNAL)) {
            return $composerJson;
        }
        $composerRequireVersion = Config::get(DevelopmentConstants::COMPOSER_REQUIRE_VERSION_EXTERNAL);

        if (preg_match('/^[0-9]/', $composerRequireVersion)) {
            $composerRequireVersion = static::RELEASE_OPERATOR . $composerRequireVersion;
        }

        foreach ($dependentModules as $dependentModule) {
            if (empty($dependentModule) || $dependentModule === $composerJson[static::KEY_NAME]) {
                continue;
            }
            $filter = new CamelCaseToDash();
            $dependentModule = strtolower($filter->filter($dependentModule));

            $composerJson[static::KEY_REQUIRE][$dependentModule] = static::RELEASE_OPERATOR . $composerRequireVersion;
        }

        return $composerJson;
    }

    /**
     * @param array $composerJsonData
     *
     * @return string
     */
    protected function getModuleName(array $composerJsonData)
    {
        $nameParts = explode('/', $composerJsonData[static::KEY_NAME]);
        $moduleName = array_pop($nameParts);
        $filter = new DashToCamelCase();

        return (string)$filter->filter($moduleName);
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function getExternalModules($bundleName)
    {
        $dependentModules = [];
        foreach ($this->externalDependencyTree as $dependency) {
            if ($dependency[DependencyTree::META_MODULE] === $bundleName
                && !in_array($dependency[DependencyTree::META_COMPOSER_NAME], $this->ignorableDependencies)
            ) {
                $dependentModules[] = $this->mapExternalToInternal($dependency[DependencyTree::META_COMPOSER_NAME]);
            }
        }
        $dependentModules = array_unique($dependentModules);
        sort($dependentModules);

        return $dependentModules;
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
