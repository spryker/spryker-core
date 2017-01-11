<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Navigation\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Navigation\Business\Model\NavigationBuilder;
use Spryker\Zed\Navigation\Business\NavigationBusinessFactory;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Navigation
 * @group Business
 * @group NavigationBusinessFactoryTest
 */
class NavigationBusinessFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Navigation\Business\NavigationBusinessFactory
     */
    private function getFactory()
    {
        return new NavigationBusinessFactory();
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
