<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

use Spryker\Zed\Development\Business\DependencyTree\Finder;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder as SFinder;
use Zend\Filter\Word\UnderscoreToCamelCase;

class BundleParser
{

    /**
     * @var array
     */
    protected $includedBundleNamespaces = ['Spryker', 'Orm'];

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
    public function __construct(SFinder $finder, DevelopmentConfig $config)
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
        $externalBundleDependencies = $this->buildExternalBundleDependencies($allFileDependencies);

        $allFileDependencies = $this->filterIncludedClasses($allFileDependencies);
        $allFileDependencies = $this->ignorePlugins($allFileDependencies);

        $bundleDependencies = $this->buildBundleDependencies($allFileDependencies, $bundleName);
        $bundleDependencies = $this->addPersistenceLayerDependencies($bundleName, $bundleDependencies);
        $bundleDependencies += $externalBundleDependencies;

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
    protected function filterIncludedClasses(array $dependencies)
    {
        $reducedDependenciesPerFile = [];
        foreach ($dependencies as $fileName => $fileDependencies) {
            $reducedDependencies = [];
            foreach ($fileDependencies as $fileDependency) {
                $fileDependencyParts = explode('\\', $fileDependency);
                $bundleNamespace = $fileDependencyParts[0];

                if (in_array($bundleNamespace, $this->includedBundleNamespaces)) {
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
     * @param array $dependencies
     *
     * @return array
     */
    protected function ignorePlugins(array $dependencies)
    {
        foreach ($dependencies as $fileName => $fileDependencies) {
            if (strpos($fileName, '/Communication/Plugin/') === false) {
                continue;
            }

            unset($dependencies[$fileName]);
        }

        return $dependencies;
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

            $filter = new UnderscoreToCamelCase();
            $name = $filter->filter($matches[1]);
            if ($name === $bundleName) {
                continue;
            }

            if (!isset($bundleDependencies[$name])) {
                $bundleDependencies[$name] = 1;
                continue;
            }

            $bundleDependencies[$name]++;
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
     * @return array
     */
    protected function buildExternalBundleDependencies(array $allFileDependencies)
    {
        $bundleDependencies = [];

        $map = $this->config->getExternalToInternalNamespaceMap();
        foreach ($allFileDependencies as $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
                $found = null;
                foreach ($map as $namespace => $package) {
                    if (strpos($fileDependency, $namespace . '\\') !== 0) {
                        continue;
                    }

                    $found = $package;
                    break;
                }

                if (!$found) {
                    continue;
                }

                $name = substr($found, 8);
                $name = str_replace('-', '_', $name);
                $filter = new UnderscoreToCamelCase();
                $name = ucfirst($filter->filter($name));

                if (!isset($bundleDependencies[$name])) {
                    $bundleDependencies[$name] = 0;
                }
                $bundleDependencies[$name]++;
            }
        }

        return $bundleDependencies;
    }

}
