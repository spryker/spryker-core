<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Dependency;


<<<<<<< HEAD
use Symfony\Component\Finder\Finder;
=======
use SprykerFeature\Zed\Maintenance\MaintenanceConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
>>>>>>> develop

class BundleParser
{

<<<<<<< HEAD
    protected $coreBundleNamespaces = ['SprykerFeature', 'SprykerEngine'];

    public function __construct()
    {

    }

=======
    const SPRYKER_ENGINE = 'SprykerEngine';
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
     * @return array
     */
>>>>>>> develop
    public function parseOutgoingDependencies($bundleName)
    {
        $allFileDependencies = $this->parseDependencies($bundleName);
        $allFileDependencies = $this->filterCoreClasses($allFileDependencies);
        $bundleDepenencies = $this->filterBundleDependencies($allFileDependencies, $bundleName);
        return $bundleDepenencies;
    }

    /**
<<<<<<< HEAD
=======
     * We only detect dependencies which are declared in the class' use statement
     *
>>>>>>> develop
     * @param $bundle
     * @return array
     */
    protected function parseDependencies($bundle)
    {
<<<<<<< HEAD
        $files = (new Finder())->files()->in(APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $bundle . '/src/*/Zed/');
            //->exclude(['Base','Map']);
=======
        $files = $this->findAllFilesOfBundle($bundle);
>>>>>>> develop

        $dependencies = [];
        foreach ($files as $file) {

<<<<<<< HEAD
            echo('<pre><b>'.print_r($file->getPath(), true).'</b>'.PHP_EOL.__CLASS__.' '.__LINE__);

            /* @var $file \Symfony\Component\Finder\SplFileInfo */
=======
>>>>>>> develop
            $content = $file->getContents();

            $matches = [];
            preg_match_all('#use (.*);#', $content, $matches);

            $dependencies[$file->getPath() . '/' . $file->getFilename()] = $matches[1];
        }
        return $dependencies;
    }

    /**
<<<<<<< HEAD
=======
     * @param $bundle
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
>>>>>>> develop
     * @param $dependencies
     * @return array
     */
    protected function filterCoreClasses($dependencies)
    {
<<<<<<< HEAD


=======
>>>>>>> develop
        $reducedDependenciesPerFile = [];
        foreach ($dependencies as $fileName => $fileDependencies) {

            $reducedDependencies = [];
            foreach ($fileDependencies as $fileDependency) {
                $expl = explode('\\', $fileDependency);
                $bundleNamespace = $expl[0];

                if (in_array($bundleNamespace, $this->coreBundleNamespaces)) {
                    $reducedDependencies[] = $fileDependency;
                }
<<<<<<< HEAD

=======
>>>>>>> develop
            }
            $reducedDependenciesPerFile[$fileName] = $reducedDependencies;
        }
        return $reducedDependenciesPerFile;
    }

    /**
     * @param $allFileDependencies
     * @param $bundle
     * @return array
     */
    protected function filterBundleDependencies($allFileDependencies, $bundle)
    {
        $bundleDepenencies = [];
        foreach ($allFileDependencies as $fileDependencies) {
            foreach ($fileDependencies as $fileDependency) {
<<<<<<< HEAD


=======
>>>>>>> develop
                $expl = explode('\\', $fileDependency);
                $foreignBundle = $expl[2];
                if ($bundle !== $foreignBundle) {
                    if (false === array_key_exists($foreignBundle, $bundleDepenencies)) {
                        $bundleDepenencies[$foreignBundle] = 0;
                    }
                    $bundleDepenencies[$foreignBundle]++;
                }
<<<<<<< HEAD


=======
>>>>>>> develop
            }
        }
        return $bundleDepenencies;
    }

<<<<<<< HEAD
    public function isEngine($bundleName)
    {
        $directories = (new Finder())->directories()->depth('== 0')->in(APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $bundleName . '/src');
        foreach ($directories as $directory) {
            if ($directory->getFilename() === 'SprykerEngine') { // TODO
=======
    /**
     * @param $bundleName
     * @return bool
     */
    public function isEngine($bundleName)
    {
        $directories = $this->findBundleNamespaceDirectoriesForBundle($bundleName);
        foreach ($directories as $directory) {
            if ($directory->getFilename() === self::SPRYKER_ENGINE) {
>>>>>>> develop
                return true;
            }
        }
        return false;
    }

<<<<<<< HEAD
=======
    /**
     * @param $bundleName
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

>>>>>>> develop

}
