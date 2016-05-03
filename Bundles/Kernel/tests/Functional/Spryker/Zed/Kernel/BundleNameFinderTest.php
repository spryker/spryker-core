<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Kernel;

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
        $bundleNameFinder = new BundleNameFinder();
        $bundleNames = $bundleNameFinder->getBundleNames();

        $this->assertTrue(count($bundleNames) > 0);
    }

}
