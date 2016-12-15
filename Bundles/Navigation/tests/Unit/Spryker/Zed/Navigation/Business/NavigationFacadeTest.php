<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Navigation\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Navigation\Business\NavigationFacade;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Navigation
 * @group Business
 * @group NavigationFacadeTest
 */
class ApplicationFacadeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Navigation\Business\NavigationFacadeInterface
     */
    protected function getFacade()
    {
        return new NavigationFacade();
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
