<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Dependency;


use Symfony\Component\Finder\Finder;

class BundleParser
{

    protected $coreBundleNamespaces = ['SprykerFeature', 'SprykerEngine'];

    public function __construct()
    {

    }

    public function parseOutgoingDependencies($bundleName)
    {
        $allFileDependencies = $this->parseDependencies($bundleName);
        $allFileDependencies = $this->filterCoreClasses($allFileDependencies);
        $bundleDepenencies = $this->filterBundleDependencies($allFileDependencies, $bundleName);
        return $bundleDepenencies;
    }

    /**
     * @param $bundle
     * @return array
     */
    protected function parseDependencies($bundle)
    {
        $files = (new Finder())->files()->in(APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $bundle . '/src/*/Zed/');

        $dependencies = [];
        foreach ($files as $file) {
            /* @var $file \Symfony\Component\Finder\SplFileInfo */
            $content = $file->getContents();

            $matches = [];
            preg_match_all('#use (.*);#', $content, $matches);

            $dependencies[$file->getPath() . '/' . $file->getFilename()] = $matches[1];
        }
        return $dependencies;
    }

    /**
     * @param $dependencies
     * @return array
     */
    protected function filterCoreClasses($dependencies)
    {


        $reducedDependenciesPerFile = [];
        foreach ($dependencies as $fileName => $fileDependencies) {

            $reducedDependencies = [];
            foreach ($fileDependencies as $fileDependency) {
                $expl = explode('\\', $fileDependency);
                $bundleNamespace = $expl[0];

                if (in_array($bundleNamespace, $this->coreBundleNamespaces)) {
                    $reducedDependencies[] = $fileDependency;
                }

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


                $expl = explode('\\', $fileDependency);
                $foreignBundle = $expl[2];
                if ($bundle !== $foreignBundle) {
                    if (false === array_key_exists($foreignBundle, $bundleDepenencies)) {
                        $bundleDepenencies[$foreignBundle] = 0;
                    }
                    $bundleDepenencies[$foreignBundle]++;
                }


            }
        }
        return $bundleDepenencies;
    }

    public function isEngine($bundleName)
    {
        $directories = (new Finder())->directories()->depth('== 0')->in(APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . $bundleName . '/src');
        foreach ($directories as $directory) {
            if ($directory->getFilename() === 'SprykerEngine') { // TODO
                return true;
            }
        }
        return false;
    }


}
