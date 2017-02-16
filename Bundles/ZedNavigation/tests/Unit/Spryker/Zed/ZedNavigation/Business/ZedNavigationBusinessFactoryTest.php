<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ZedNavigation\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\ZedNavigation\Business\Model\ZedNavigationBuilder;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group ZedNavigationBusinessFactoryTest
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

        $this->assertInstanceOf(NavigationBuilder::class, $instance);
    }

}
