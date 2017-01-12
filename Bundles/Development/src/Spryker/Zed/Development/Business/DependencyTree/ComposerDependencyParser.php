<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree;

use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\Word\UnderscoreToCamelCase;

class ComposerDependencyParser
{

    const TYPE_INCLUDE = 'include';
    const TYPE_EXCLUDE = 'exclude';
    const TYPE_INCLUDE_DEV = 'include-dev';
    const TYPE_EXCLUDE_DEV = 'exclude-dev';

    /**
     * @var \Spryker\Zed\Development\Business\Composer\ComposerJsonFinder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Development\Business\Composer\ComposerJsonFinder $finder
     */
    public function __construct($finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $bundleName
     * @param array $codeDependencies
     *
     * @return array
     */
    public function getComposerDependencyComparison($bundleName, $codeDependencies)
    {
        $codeDependencies = $this->getOverwrittenDependenciesForBundle($bundleName, $codeDependencies);
        $codeDependencies = $this->filterCodeDependencies($codeDependencies);

        $composerDependencies = $this->getParsedComposerDependenciesForBundle($bundleName);

        $together = array_unique(array_merge($codeDependencies, $composerDependencies));
        sort($together);

        $dependencies = [];

        foreach ($together as $bundleName) {
            $dependencies[] = [
                'code' => in_array($bundleName, $codeDependencies) ? $bundleName : '',
                'composer' => in_array($bundleName, $composerDependencies) ? $bundleName : '',
            ];
        }

        return $dependencies;
    }

    /**
     * @param string $bundleName
     * @param array $codeDependencies
     *
     * @return array
     */
    protected function getOverwrittenDependenciesForBundle($bundleName, array $codeDependencies)
    {
        $declaredDependencies = $this->parseDeclaredDependenciesForBundle($bundleName);
        if (!$declaredDependencies) {
            return $codeDependencies;
        }

        // For now we can't separate in the dependency tool yet
        $included = array_merge($declaredDependencies[static::TYPE_INCLUDE], $declaredDependencies[static::TYPE_INCLUDE_DEV]);
        $excluded = array_merge($declaredDependencies[static::TYPE_EXCLUDE], $declaredDependencies[static::TYPE_EXCLUDE_DEV]);

        foreach ($codeDependencies as $key => $bundleDependency) {
            if (in_array($bundleDependency, $excluded)) {
                unset($codeDependencies[$key]);
            }
        }

        $codeDependencies = array_merge($codeDependencies, $included);

        return $codeDependencies;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function parseDeclaredDependenciesForBundle($bundleName)
    {
        $composerJsonFiles = $this->finder->find();
        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $bundleName)) {
                continue;
            }

            $path = dirname((string)$composerJsonFile);
            $dependencyFile = $path . DIRECTORY_SEPARATOR . 'dependency.json';
            if (!file_exists($dependencyFile)) {
                return [];
            }

            $content = file_get_contents($dependencyFile);
            $content = json_decode($content, true);

            return [
                static::TYPE_INCLUDE => isset($content[static::TYPE_INCLUDE]) ? array_keys($content[static::TYPE_INCLUDE]) : [],
                static::TYPE_EXCLUDE => isset($content[static::TYPE_EXCLUDE]) ? array_keys($content[static::TYPE_EXCLUDE]) : [],
                static::TYPE_INCLUDE_DEV => isset($content[static::TYPE_INCLUDE_DEV]) ? array_keys($content[static::TYPE_INCLUDE_DEV]) : [],
                static::TYPE_EXCLUDE_DEV => isset($content[static::TYPE_EXCLUDE_DEV]) ? array_keys($content[static::TYPE_EXCLUDE_DEV]) : [],
            ];
        }

        return [];
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    protected function getParsedComposerDependenciesForBundle($bundleName)
    {
        $composerJsonFiles = $this->finder->find();
        $dependencies = [];
        foreach ($composerJsonFiles as $composerJsonFile) {
            if ($this->shouldSkip($composerJsonFile, $bundleName)) {
                continue;
            }

            $content = file_get_contents($composerJsonFile);
            $content = json_decode($content, true);
            $require = isset($content['require']) ? $content['require'] : [];

            foreach ($require as $package => $version) {
                if (strpos($package, 'spryker/') !== 0) {
                    continue;
                }

                $name = substr($package, 8);
                $name = str_replace('-', '_', $name);
                $filter = new UnderscoreToCamelCase();
                $name = ucfirst($filter->filter($name));

                $dependencies[] = $name;
            }
        }

        return $dependencies;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     * @param string $bundleName
     *
     * @return bool
     */
    protected function shouldSkip(SplFileInfo $composerJsonFile, $bundleName)
    {
        $folder = $composerJsonFile->getRelativePath();

        return ($folder !== $bundleName);
    }

    /**
     * @TODO find better way to handle this:
     *
     * Propel bundle is separated into two bundles.
     *
     * "spryker/propel-orm" for the dependency to the external "propel/propel"
     * "spryker/propel" for our own code like Builders etc.
     *
     * "spryker/propel-orm" is a dependency of "spryker/propel" but
     * is displayed in the list of Composer dependencies. To prevent this wrong
     * dependency "alert" PropelOrm gets filtered out when both bundles are present.
     *
     * @param array $codeDependencies
     *
     * @return array
     */
    private function filterCodeDependencies(array $codeDependencies)
    {
        if (in_array('Propel', $codeDependencies) && in_array('PropelOrm', $codeDependencies)) {
            unset($codeDependencies[array_search('PropelOrm', $codeDependencies)]);
        }

        return $codeDependencies;
    }

}
