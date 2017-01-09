<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Application\Business\ApplicationBusinessFactory;
use Spryker\Zed\Application\Business\Model\Navigation\NavigationBuilder;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group ApplicationBusinessFactoryTest
 */
class ApplicationBusinessFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Application\Business\ApplicationBusinessFactory
     */
    private function getFactory()
    {
        return new ApplicationBusinessFactory();
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
