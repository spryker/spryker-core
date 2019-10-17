<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Facade
 * @group ZedNavigationFacadeTest
 * Add your own group annotations below this line
 */
class ZedNavigationFacadeTest extends ZedNavigationBusinessTester
{
    /**
     * @return void
     */
    public function testBuildNavigationShouldReturnArrayWithMenuAsKey()
    {
        $navigation = $this->getFacade()->buildNavigation('');

        $this->assertArrayHasKey('menu', $navigation);
    }
}
