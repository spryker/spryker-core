<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ZedNavigation\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\ZedNavigation\Business\ZedNavigationFacade;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group ZedNavigationFacadeTest
 */
class ZedNavigationFacadeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface
     */
    protected function getFacade()
    {
        return new ZedNavigationFacade();
    }

    /**
     * @return void
     */
    public function testBuildNavigationShouldReturnArrayWithMenuAsKey()
    {
        $navigation = $this->getFacade()->buildNavigation('');

        $this->assertArrayHasKey('menu', $navigation);
    }

}
