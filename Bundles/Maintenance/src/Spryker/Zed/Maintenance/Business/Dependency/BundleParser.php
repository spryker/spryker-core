<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Dependency;

use Spryker\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BundleParser
{

    const CONFIG_FILE = 'bundle_config.json';

    /**
     * @var array
     */
    protected $coreBundleNamespaces = ['Spryker'];

    /**
     * @var MaintenanceConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $bundleConfig;

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
            ->exclude($this->config->getExcludedDirectoriesForDependencies());

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
                    if (array_key_exists($foreignBundle, $bundleDependencies) === false) {
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
        $config = $this->getBundleConfig();
        if (empty($config[$bundleName])) {
            return false;
        }

        return $config[$bundleName] === 'engine';
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
            ->in($this->config->getBundleDirectory() . $bundleName . '/src');

        return $directories;
    }

    /**
     * @return array
     */
    protected function getBundleConfig() {
        if (isset($this->bundleConfig)) {
            return $this->bundleConfig;
        }
        $file = APPLICATION_VENDOR_DIR
            . DIRECTORY_SEPARATOR . 'spryker'
            . DIRECTORY_SEPARATOR . 'spryker'
            . DIRECTORY_SEPARATOR . self::CONFIG_FILE;

        $this->bundleConfig = json_decode(file_get_contents($file), true);
        return $this->bundleConfig;
    }

}
