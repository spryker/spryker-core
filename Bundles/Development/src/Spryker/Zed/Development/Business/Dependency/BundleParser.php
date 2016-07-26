<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Spryker\Zed\Development\Business\DependencyTree\Finder;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Zend\Filter\Word\UnderscoreToCamelCase;

class BundleParser
{

    /**
     * @var array
     */
    protected $relevantBundleNamespaces = ['Spryker', 'Orm'];

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $bundleConfig;

    /**
     * @param \Symfony\Component\Finder\Finder $finder
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(SymfonyFinder $finder, DevelopmentConfig $config)
    {
        $this->finder = $finder;
        $this->config = $config;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function parseOutgoingDependencies($bundleName)
    {
        $allFileDependencies = $this->parseDependencies($bundleName);
        $externalBundleDependencies = $this->buildExternalBundleDependencies($allFileDependencies, $bundleName);
        $locatorBundleDependencies = $this->buildLocatorBundleDependencies($allFileDependencies, $bundleName);

        $allFileDependencies = $this->filterRelevantClasses($allFileDependencies);
        $allFileDependencies = $this->ignorePluginInterfaces($allFileDependencies);

        $bundleDependencies = $this->buildBundleDependencies($allFileDependencies, $bundleName);
        $bundleDependencies = $this->addPersistenceLayerDependencies($bundleName, $bundleDependencies);
        $bundleDependencies += $externalBundleDependencies;
        $bundleDependencies += $locatorBundleDependencies;

        return $bundleDependencies;
    }

    /**
     * We only detect dependencies which are declared in the class' use statement
     *
     * @param string $bundle
     *
     * @return array
     */
    protected function parseDependencies($bundle)
    {
        $files = $this->findAllFilesOfBundle($bundle);

        $dependencies = [];
        foreach ($files as $file) {
            $content = $file->getContents();

            $matches = [];
            preg_match_all('#use (.*);#', $content, $matches);

            $dependencies[$file->getPath() . '/' . $file->getFilename()] = $matches[1];
        }

        return $dependencies;
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    protected function isExistentBundle($bundle)
    {
        return is_dir($this->config->getBundleDirectory() . $bundle);
    }

    /**
     * @param string $bundle
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    protected function findAllFilesOfBundle($bundle)
    {
        $finder = new Finder($this->config->getBundleDirectory(), '*', $bundle);

        return $finder->getFiles();
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    protected function filterRelevantClasses(array $dependencies)
    {
        $reducedDependenciesPerFile = [];
        foreach ($dependencies as $fileName => $fileDependencies) {
            $reducedDependencies = [];
            foreach ($fileDependencies as $fileDependency) {
                $fileDependencyParts = explode('\\', $fileDependency);
                $bundleNamespace = $fileDependencyParts[0];

                if (in_array($bundleNamespace, $this->relevantBundleNamespaces)) {
                    $reducedDependencies[] = $fileDependency;
                }
            }
            $reducedDependenciesPerFile[$fileName] = $reducedDependencies;
        }

        return $reducedDependenciesPerFile;
    }

    /**
     * @param array $allFileDependencies
     * @param string $bundle
     *
     * @return array
     */
    protected function buildBundleDependencies(array $allFileDependencies, $bundle)
    {
        $bundleDependencies = [];
        foreach ($allFileDependencies as $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
                $expl = explode('\\', $fileDependency);
                $foreignBundle = $expl[2];
                if ($bundle !== $foreignBundle) {
                    if (array_key_exists($foreignBundle, $bundleDependencies) === false) {
                        $bundleDependencies[$foreignBundle] = 0;
                    }
                    $bundleDependencies[$foreignBundle]++;
                }
            }
        }

        ksort($bundleDependencies);

        return $bundleDependencies;
    }

    /**
     * @param string $bundleName
     * @param array $bundleDependencies
     *
     * @return array
     */
    protected function addPersistenceLayerDependencies($bundleName, array $bundleDependencies)
    {
        $folder = $this->config->getBundleDirectory() . $bundleName . '/src/Spryker/Zed/' . $bundleName . '/Persistence/Propel/Schema/';
        if (!is_dir($folder)) {
            return $bundleDependencies;
        }

        $files = $this->find($folder);

        foreach ($files as $file) {
            preg_match('/^spy\_(.+)\.schema\.xml$/', $file->getRelativePathname(), $matches);
            if (!$matches) {
                continue;
            }

            $name = $matches[1];

            $content = file_get_contents($file->getPathname());
            preg_match_all('/\bforeignTable="spy\_(.+?)"/', $content, $tableMatches);

            $tables = $tableMatches[1];
            foreach ($tables as $key => $value) {
                if (strpos($value, $name) === 0) {
                    unset($tables[$key]);
                }
            }

            if (!$tables) {
                continue;
            }

            foreach ($tables as $table) {
                $bundleDependencies = $this->checkForPersistenceLayerDependency($table, $bundleDependencies, $bundleName);
            }
        }

        return $bundleDependencies;
    }

    /**
     * @param string
     *
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function find($folder)
    {
        return $this->finder->in($folder)->name('*.schema.xml')->depth('< 2');
    }

    /**
     * @param array $allFileDependencies
     * @param string $currentBundleName
     *
     * @return array
     */
    protected function buildExternalBundleDependencies(array $allFileDependencies, $currentBundleName)
    {
        $bundleDependencies = [];

        $map = $this->config->getExternalToInternalNamespaceMap();

        foreach ($allFileDependencies as $file => $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
                $found = null;
                foreach ($map as $namespace => $package) {
                    if (strpos($fileDependency, $namespace) !== 0) {
                        continue;
                    }

                    $found = $package;
                    break;
                }

                if ($found === null) {
                    continue;
                }

                $name = substr($found, 8);
                $name = str_replace('-', '_', $name);
                $filter = new UnderscoreToCamelCase();
                $name = ucfirst($filter->filter($name));

                $bundleDependencies = $this->addDependency($name, $bundleDependencies, $currentBundleName);
            }
        }

        return $bundleDependencies;
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    protected function ignorePluginInterfaces(array $dependencies)
    {
        foreach ($dependencies as $fileName => $fileDependencies) {
            if (strpos($fileName, '/Communication/Plugin/') === false) {
                continue;
            }

            foreach ($fileDependencies as $key => $fileDependency) {
                if (!preg_match('#\\\\Dependency\\\\.*Plugin.*Interface$#', $fileDependency)) {
                    continue;
                }

                unset($dependencies[$fileName][$key]);
            }
        }

        return $dependencies;
    }

    /**
     * @param array $allFileDependencies
     * @param string $bundleName
     *
     * @return array
     */
    protected function buildLocatorBundleDependencies($allFileDependencies, $bundleName)
    {
        $dependencies = [];

        foreach ($allFileDependencies as $fileName => $fileDependencies) {
            if (!$fileDependencies || strpos($fileName, 'DependencyProvider.php') === false) {
                continue;
            }

            $dependencies += $this->extractDependenciesFromDependencyProvider($fileName, $bundleName);
        }

        return $dependencies;
    }

    /**
     * @param string $fileName
     * @param string $bundleName;
     *
     * @return array
     */
    protected function extractDependenciesFromDependencyProvider($fileName, $bundleName)
    {
        $content = file_get_contents($fileName);

        if (!preg_match_all('/->(?<bundle>\w+?)\(\)->(client|facade|queryContainer)\(\)/', $content, $matches, PREG_SET_ORDER)) {
            return [];
        }

        $dependencies = [];

        foreach ($matches as $match) {
            $toBundle = ucfirst($match['bundle']);

            $dependencies = $this->addDependency($toBundle, $dependencies, $bundleName);
        }

        return $dependencies;
    }

    /**
     * @param string $table
     * @param array $bundleDependencies
     * @param string $currentBundle
     *
     * @return array
     */
    protected function checkForPersistenceLayerDependency($table, array $bundleDependencies, $currentBundle)
    {
        $filter = new UnderscoreToCamelCase();
        $name = $filter->filter($table);

        $existent = $this->isExistentBundle($name);
        if ($existent) {
            $bundleDependencies = $this->addDependency($name, $bundleDependencies, $currentBundle);
            return $bundleDependencies;
        }

        $lastUnderscore = strrpos($table, '_');
        while ($lastUnderscore) {
            $table = substr($table, 0, $lastUnderscore);

            $filter = new UnderscoreToCamelCase();
            $name = $filter->filter($table);

            $existent = $this->isExistentBundle($name);
            if (!$existent) {
                $lastUnderscore = strrpos($table, '_');
                continue;
            }

            $this->addDependency($name, $bundleDependencies, $currentBundle);
            break;
        }

        return $bundleDependencies;
    }

    /**
     * @param string $name
     * @param array $bundleDependencies
     * @param string|null $currentBundleName
     *
     * @return array
     */
    protected function addDependency($name, array $bundleDependencies, $currentBundleName = null)
    {
        if ($currentBundleName !== null && $name === $currentBundleName) {
            return $bundleDependencies;
        }

        if (!isset($bundleDependencies[$name])) {
            $bundleDependencies[$name] = 0;
        }

        $bundleDependencies[$name]++;

        return $bundleDependencies;
    }

}
