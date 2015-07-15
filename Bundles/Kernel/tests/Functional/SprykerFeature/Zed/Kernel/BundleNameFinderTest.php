<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Kernel;

use SprykerEngine\Zed\Kernel\BundleNameFinder;

/**
 * @group Kernel
 * @group BundleNameFinder
 */
class BundleNameFinderTest extends \PHPUnit_Framework_TestCase
{

    public function testBundleNameFinderShouldFindBundleNames()
    {
        $bundleNameFinder = new BundleNameFinder();
        $bundleNames = $bundleNameFinder->getBundleNames();

        $this->assertContains('Kernel', $bundleNames);
    }

}
