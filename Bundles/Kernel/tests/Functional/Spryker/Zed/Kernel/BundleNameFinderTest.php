<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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

        $this->assertContains('Kernel', $bundleNames);
    }

}
