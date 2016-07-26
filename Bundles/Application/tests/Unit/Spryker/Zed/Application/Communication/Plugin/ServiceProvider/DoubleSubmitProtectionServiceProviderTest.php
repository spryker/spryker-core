<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\TestCase\Test;
use Silex\Application;
use Spryker\Shared\Application\Communication\Plugin\ServiceProvider\DoubleSubmitProtectionServiceProvider;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @group Zed
 * @group Application
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group DoubleSubmitProtectionServiceProvider
 */
class DoubleSubmitProtectionServiceProviderTest extends Test
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $application;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage
     */
    protected $sessionStorage;

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
            function ($app) {}
        );
        $application['session'] = $this->getMock(SessionInterface::class);

        return $application;
    }

}
