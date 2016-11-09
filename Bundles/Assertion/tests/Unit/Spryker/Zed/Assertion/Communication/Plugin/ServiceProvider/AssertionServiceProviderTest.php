<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Assertion\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Spryker\Zed\Assertion\Business\AssertionFacade;
use Spryker\Zed\Assertion\Communication\Plugin\ServiceProvider\AssertionServiceProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Assertion
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group AssertionServiceProviderTest
 */
class AssertionServiceProviderTest extends \PHPUnit_Framework_TestCase
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
