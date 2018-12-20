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
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationExtensionInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationExtensionInterface;

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
    public function testApplicationRegisterApplicationExtension(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);

        //Act
        $application->registerApplicationExtension($this->createApplicationExtension());

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testApplicationRunBootableApplicationExtensions(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);

        //Act
        $application->registerApplicationExtension($this->createBootableApplicationExtension());
        $application->boot();

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testApplicationRunsBootableApplicationExtensionsOnlyOnce(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);
        $serviceProvider = $this->createBootableApplicationExtension();

        //Act
        $application->registerApplicationExtension($serviceProvider);
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
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationExtensionInterface
     */
    protected function createApplicationExtension(): ApplicationExtensionInterface
    {
        return new class implements ApplicationExtensionInterface
        {
            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return void
             */
            public function provideExtension(ContainerInterface $container): void
            {
                $container->set(ApplicationTest::SERVICE, function () {
                    return [ApplicationTest::SERVICE_PROPERTY => true];
                });
            }
        };
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationExtensionInterface
     */
    protected function createBootableApplicationExtension(): ApplicationExtensionInterface
    {
        return new class implements ApplicationExtensionInterface, BootableApplicationExtensionInterface
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
            public function provideExtension(ContainerInterface $container): void
            {
            }

            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return void
             */
            public function bootExtension(ContainerInterface $container): void
            {
                $this->runs++;
                $container->set(ApplicationTest::SERVICE, function () {
                    return [ApplicationTest::SERVICE_PROPERTY => true];
                });
            }
        };
    }
}
