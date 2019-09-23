<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Assertion\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Assertion\Business\AssertionFacade;
use Spryker\Zed\Assertion\Communication\Plugin\ServiceProvider\AssertionServiceProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Assertion
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group AssertionServiceProviderTest
 * Add your own group annotations below this line
 */
class AssertionServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterServiceProvider()
    {
        $application = new Application();

        $serviceProvider = new AssertionServiceProvider();
        $serviceProvider->register($application);

        $this->assertInstanceOf(AssertionFacade::class, $application[AssertionServiceProvider::ASSERTION]);
    }
}
