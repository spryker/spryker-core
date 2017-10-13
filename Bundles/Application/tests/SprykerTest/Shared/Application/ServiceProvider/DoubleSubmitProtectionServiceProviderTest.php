<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Shared\Application\ServiceProvider\DoubleSubmitProtectionServiceProvider;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group ServiceProvider
 * @group DoubleSubmitProtectionServiceProviderTest
 * Add your own group annotations below this line
 */
class DoubleSubmitProtectionServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testServiceProviderInApplicationRegistration()
    {
        $serviceProvider = new DoubleSubmitProtectionServiceProvider();
        $application = $this->createApplication();
        $serviceProvider->register($application);
        $serviceProvider->boot($application);

        $formExtensions = $application['form.extensions'];

        $this->assertNotEmpty($formExtensions);
        $extension = array_pop($formExtensions);

        $this->assertInstanceOf(DoubleSubmitProtectionExtension::class, $extension);
    }

    /**
     * @return \Silex\Application
     */
    protected function createApplication()
    {
        $application = new Application();
        $application['form.extensions'] = $application->share(
            function ($app) {
            }
        );
        $application['session'] = $this->getMockBuilder(SessionInterface::class)->getMock();

        return $application;
    }
}
