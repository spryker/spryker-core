<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Dependency;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Manager
{

    /**
     * @var BundleParser
     */
    protected $bundleParser;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param BundleParser $bundleParser
     */
    public function __construct(BundleParser $bundleParser)
    {
        $this->bundleParser = $bundleParser;
    }

    /**
     * @param string $bundleName
     *
     * @return array
     */
    public function parseIncomingDependencies($bundleName)
    {
        $allForeignBundles = $this->collectAllForeignBundles($bundleName);

        $incomingDependencies = [];
        foreach ($allForeignBundles as $foreignBundle) {
            try {
                $dependencies = $this->bundleParser->parseOutgoingDependencies($foreignBundle);
            } catch (\Exception $e) {
                $dependencies = []; // TODO illegal try-catch
            }
            if (array_key_exists($bundleName, $dependencies)) {
                if (false === array_key_exists($foreignBundle, $incomingDependencies)) {
                    $incomingDependencies[$foreignBundle] = 0;
                }
                $incomingDependencies[$foreignBundle] += $dependencies[$bundleName];
            }
        }

        return $incomingDependencies;
    }

    /**
     * @param $bundleName
     *
     * @return array
     */
    protected function collectAllForeignBundles($bundleName)
    {
        $allForeignBundles = [];

        $pathToBundles = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/';
        $bundles = (new Finder())->directories()->depth('== 0')->in($pathToBundles);

        /** @var $bundle SplFileInfo */
        foreach ($bundles as $bundle) {
            $foreignBundleName = $bundle->getFilename();
            if ($foreignBundleName !== $bundleName) {
                $allForeignBundles[] = $foreignBundleName;
            }
        }

        return $allForeignBundles;
    }

}
