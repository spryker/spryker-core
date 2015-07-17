<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

use SprykerEngine\Shared\Kernel\AbstractBundle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class BundleNameFinder extends AbstractBundle
{

    /**
     * @return array
     */
    public function getBundleNames()
    {
        $bundles = [];

        $finder = new Finder();
        $dirs = $this->getBundleDirectories();
        /** @var SplFileInfo $bundleDirectory */
        foreach ($finder->directories()->in($dirs)->depth(0) as $bundleDirectory) {
            $bundleName = $bundleDirectory->getRelativePathname();
            $bundles[] = $bundleName;
        }

        $bundles = array_unique($bundles);

        sort($bundles);

        return $bundles;
    }

    /**
     * @return array
     */
    private function getBundleDirectories()
    {
        $vendorBundlePathPattern = rtrim($this->options[self::OPTION_KEY_VENDOR_PATH_PATTERN], DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . $this->options[self::OPTION_KEY_BUNDLE_PATH_PATTERN]
            . $this->options[self::OPTION_KEY_APPLICATION]
        ;

        $projectBundlePathPattern = rtrim($this->options[self::OPTION_KEY_PROJECT_PATH_PATTERN])
            . DIRECTORY_SEPARATOR
            . $this->options[self::OPTION_KEY_BUNDLE_PROJECT_PATH_PATTERN]
            . $this->options[self::OPTION_KEY_APPLICATION]
        ;

        $dirs = [
            $projectBundlePathPattern,
            $vendorBundlePathPattern,
        ];

        return $dirs;
    }

}
