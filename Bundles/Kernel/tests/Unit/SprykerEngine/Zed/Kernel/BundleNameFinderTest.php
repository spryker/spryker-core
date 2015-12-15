<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\BundleNameFinder;

/**
 * @group Kernel
 * @group BundleNameFinder
 */
class BundleNameFinderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBundleNameFinderShouldFindBundleNames()
    {
        $options = [
            BundleNameFinder::OPTION_KEY_PROJECT_PATH_PATTERN => __DIR__ . '/Fixtures/Project/src/',
            BundleNameFinder::OPTION_KEY_VENDOR_PATH_PATTERN => __DIR__ . '/Fixtures/Vendor/*/src/',
            BundleNameFinder::OPTION_KEY_APPLICATION => 'Application',
        ];

        $bundleNameFinder = new BundleNameFinder($options);
        $bundleNames = $bundleNameFinder->getBundleNames();

        $this->assertContains('FooBundle', $bundleNames);
        $this->assertContains('BarBundle', $bundleNames);
    }

}
