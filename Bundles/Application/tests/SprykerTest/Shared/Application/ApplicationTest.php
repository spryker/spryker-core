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
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;

/**
 * Auto-generated group annotations
 *
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
    public function testApplicationRegisterApplicationPlugin(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);

        //Act
        $application->registerApplicationPlugin($this->createApplicationPlugin());

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testApplicationRunBootableApplicationPlugins(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);

        //Act
        $application->registerApplicationPlugin($this->createBootableApplicationPlugin());
        $application->boot();

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testApplicationRunsBootableApplicationPluginsOnlyOnce(): void
    {
        //Arrange
        $container = $this->createContainer();
        $application = $this->createApplication($container);
        $serviceProvider = $this->createBootableApplicationPlugin();

        //Act
        $application->registerApplicationPlugin($serviceProvider);
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
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface
     */
    protected function createApplicationPlugin(): ApplicationPluginInterface
    {
        return new class implements ApplicationPluginInterface
        {
            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return \Spryker\Service\Container\ContainerInterface
             */
            public function provide(ContainerInterface $container): ContainerInterface
            {
                $container->set(ApplicationTest::SERVICE, function () {
                    return [ApplicationTest::SERVICE_PROPERTY => true];
                });

                return $container;
            }
        };
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface
     */
    protected function createBootableApplicationPlugin(): ApplicationPluginInterface
    {
        return new class implements ApplicationPluginInterface, BootableApplicationPluginInterface
        {
            /**
             * @var int
             */
            public $runs = 0;

            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return \Spryker\Service\Container\ContainerInterface
             */
            public function provide(ContainerInterface $container): ContainerInterface
            {
                return $container;
            }

            /**
             * @param \Spryker\Service\Container\ContainerInterface $container
             *
             * @return \Spryker\Service\Container\ContainerInterface
             */
            public function boot(ContainerInterface $container): ContainerInterface
            {
                $this->runs++;
                $container->set(ApplicationTest::SERVICE, function () {
                    return [ApplicationTest::SERVICE_PROPERTY => true];
                });

                return $container;
            }
        };
    }
}
