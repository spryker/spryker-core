<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Dependency;

use Symfony\Component\Finder\Finder;

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

    public function __construct(BundleParser $bundleParser)
    {
        $this->bundleParser = $bundleParser;
    }

    public function parseIncomingDependencies($bundleName)
    {
        $allForeignBundles = $this->collectAllForeignBundles($bundleName);

        $incomingDependencies = [];
        foreach ($allForeignBundles as $foreignBundle) {

            try {
                $dependencies = $this->bundleParser->parseOutgoingDependencies($foreignBundle, true);
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

        $bundles = (new Finder())->directories()->depth('== 0')->in(APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/');
        foreach ($bundles as $bundle) {
            /* @var $bundle \Symfony\Component\Finder\SplFileInfo */
            $foreignBundleName = $bundle->getFilename();
            if ($foreignBundleName !== $bundleName) {
                $allForeignBundles[] = $foreignBundleName;
            }
        }

        return $allForeignBundles;
    }

}
