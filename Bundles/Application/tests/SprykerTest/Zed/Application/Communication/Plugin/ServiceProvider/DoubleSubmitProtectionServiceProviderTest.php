<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\DoubleSubmitProtectionServiceProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Application
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group DoubleSubmitProtectionServiceProviderTest
 * Add your own group annotations below this line
 */
class DoubleSubmitProtectionServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testServiceProviderCanBeRegisteredInTheApplication()
    {
        // Arrange
        $serviceProvider = new DoubleSubmitProtectionServiceProvider();
        $container = $this->createContainerMock();
        $serviceProvider->register($container);
        $serviceProvider->boot($container);

        // Act
        $formExtensions = $container->get('form.extensions');

        // Assert
        $this->assertNotEmpty($formExtensions);
        $extension = array_pop($formExtensions);

        $this->assertInstanceOf(DoubleSubmitProtectionExtension::class, $extension);
    }

    /**
     * @return \Silex\Application|\Spryker\Service\Container\ContainerInterface
     */
    protected function createContainerMock()
    {
        $application = new Application();
        $application->set('form.extensions', function ($app) {
        });
        $application->set('session', $this->getMockBuilder(SessionInterface::class)->getMock());

        return $application;
    }
}
