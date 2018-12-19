<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\ApplicationExtension\Provider\BootableServiceInterface;
use Spryker\Shared\ApplicationExtension\Provider\ServiceProviderInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group ApplicationTest
 * Add your own group annotations below this line
 */
class ApplicationTest extends Unit
{
    public const SERVICE = 'SERVICE';
    public const SERVICE_PROPERTY = 'SERVICE_PROPERTY';

    /**
     * @return void
     */
    public function testApplicationRegisterServiceProvider(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);

        //Act
        $application->registerServiceProvider($this->createServiceProvider());

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testApplicationRunBootableService(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);

        //Act
        $application->registerServiceProvider($this->createBootableServiceProvider());
        $application->boot();

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testApplicationRunsBootableServicesOnlyOnce(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);
        $serviceProvider = $this->createBootableServiceProvider();

        //Act
        $application->registerServiceProvider($serviceProvider);
        $application->boot();
        $application->boot();

        //Assert
        $this->assertSame(1, $serviceProvider->runs);
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function createContainer(): ContainerInterface
    {
        return new Container();
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\Application\Application
     */
    protected function createApplication(ContainerInterface $container): Application
    {
        return new Application($container);
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Provider\ServiceProviderInterface
     */
    protected function createServiceProvider(): ServiceProviderInterface
    {
        return new class implements ServiceProviderInterface
        {
            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return void
             */
            public function provide(ContainerInterface $container): void
            {
                $container->set(ApplicationTest::SERVICE, function () {
                    return [ApplicationTest::SERVICE_PROPERTY => true];
                });
            }
        };
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Provider\BootableServiceInterface
     */
    protected function createBootableServiceProvider(): BootableServiceInterface
    {
        return new class implements ServiceProviderInterface, BootableServiceInterface
        {
            /**
             * @var int
             */
            public $runs = 0;

            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return void
             */
            public function provide(ContainerInterface $container): void
            {
            }

            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return void
             */
            public function boot(ContainerInterface $container): void
            {
                $this->runs++;
                $container->set(ApplicationTest::SERVICE, function () {
                    return [ApplicationTest::SERVICE_PROPERTY => true];
                });
            }
        };
    }
}
