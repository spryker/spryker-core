<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Dependency;

use SprykerFeature\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BundleParser
{

    const SPRYKER_ENGINE = 'SprykerEngine';

    /**
     * @var array
     */
    protected $coreBundleNamespaces = ['SprykerFeature', self::SPRYKER_ENGINE];

    /**
     * @var MaintenanceConfig
     */
    protected $config;

    /**
     * @param MaintenanceConfig $config
     */
    public function __construct(MaintenanceConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param $bundleName
     *
     * @return array
     */
    public function parseOutgoingDependencies($bundleName)
    {
        $allFileDependencies = $this->parseDependencies($bundleName);
        $allFileDependencies = $this->filterCoreClasses($allFileDependencies);
        $bundleDependencies = $this->filterBundleDependencies($allFileDependencies, $bundleName);

        return $bundleDependencies;
    }

    /**
     * We only detect dependencies which are declared in the class' use statement
     *
     * @param $bundle
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
     * @param $bundle
     *
     * @return SplFileInfo[]
     */
    protected function findAllFilesOfBundle($bundle)
    {
        $files = (new Finder())
            ->files()
            ->in($this->config->getBundleDirectory() . $bundle . '/src/*/Zed/')
            ->exclude($this->config->getExcludedDirectoriesForDependencies())
        ;

        return $files;
    }

    /**
     * @param array $dependencies
     *
     * @return array
     */
    protected function filterCoreClasses(array $dependencies)
    {
        $reducedDependenciesPerFile = [];
        foreach ($dependencies as $fileName => $fileDependencies) {
            $reducedDependencies = [];
            foreach ($fileDependencies as $fileDependency) {
                $fileDependencyParts = explode('\\', $fileDependency);
                $bundleNamespace = $fileDependencyParts[0];

                if (in_array($bundleNamespace, $this->coreBundleNamespaces)) {
                    $reducedDependencies[] = $fileDependency;
                }
            }
            $reducedDependenciesPerFile[$fileName] = $reducedDependencies;
        }

        return $reducedDependenciesPerFile;
    }

    /**
     * @param array $allFileDependencies
     * @param $bundle
     *
     * @return array
     */
    protected function filterBundleDependencies(array $allFileDependencies, $bundle)
    {
        $bundleDependencies = [];
        foreach ($allFileDependencies as $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
                $expl = explode('\\', $fileDependency);
                $foreignBundle = $expl[2];
                if ($bundle !== $foreignBundle) {
                    if (false === array_key_exists($foreignBundle, $bundleDependencies)) {
                        $bundleDependencies[$foreignBundle] = 0;
                    }
                    $bundleDependencies[$foreignBundle]++;
                }
            }
        }

        return $bundleDependencies;
    }

    /**
     * @param string $bundleName
     *
     * @return bool
     */
    public function isEngine($bundleName)
    {
        $directories = $this->findBundleNamespaceDirectoriesForBundle($bundleName);
        foreach ($directories as $directory) {
            if ($directory->getFilename() === self::SPRYKER_ENGINE) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $bundleName

     *
     * @return SplFileInfo[]
     */
    protected function findBundleNamespaceDirectoriesForBundle($bundleName)
    {
        $directories = (new Finder())
            ->directories()
            ->depth('== 0')
            ->in($this->config->getBundleDirectory() . $bundleName . '/src')
        ;

        return $directories;
    }

}
