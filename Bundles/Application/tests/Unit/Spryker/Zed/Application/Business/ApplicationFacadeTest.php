<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business;

use Spryker\Zed\Application\Business\ApplicationFacade;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group ApplicationFacadeTest
 */
class ApplicationFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Application\Business\ApplicationFacadeInterface
     */
    private function getFacade()
    {
        return new ApplicationFacade();
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
