<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\ZedNavigation\Business\Model\ZedNavigationBuilder;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group ZedNavigationBusinessFactoryTest
 * Add your own group annotations below this line
 */
class ZedNavigationBusinessFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory
     */
    private function getFactory()
    {
        return new ZedNavigationBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreateNavigationBuilderShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getFactory()->createNavigationBuilder();

        $this->assertInstanceOf(ZedNavigationBuilder::class, $instance);
    }

}
