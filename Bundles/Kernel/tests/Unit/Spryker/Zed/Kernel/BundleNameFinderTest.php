<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\BundleNameFinder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group BundleNameFinderTest
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
