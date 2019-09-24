<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Container;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Container
 * @group ContainerBackwardsCompatibilityTest
 * Add your own group annotations below this line
 */
class ContainerBackwardsCompatibilityTest extends Unit
{
    protected const SERVICE = 'service';

    /**
     * @return void
     */
    public function testArrayAccessSetAddsService(): void
    {
        //Arrange
        $container = new Container();

        //Act
        $container[static::SERVICE] = function () {
        };

        //Assert
        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testArrayAccessSetAddsServiceAsFactory(): void
    {
        //Arrange
        $container = new Container();
        $container[static::SERVICE] = function () {
            return new class {
            };
        };

        //Act
        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

        //Assert
        $this->assertNotSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testArrayAccessSetDoesNotAddServiceWhenExtendWasCalledBefore(): void
    {
        $container = new Container();
        $container[static::SERVICE] = $container->extend(static::SERVICE, function ($service) {
            return $service . 'bar';
        });

        $container->set(static::SERVICE, function () {
            return 'foo';
        });

        $this->assertSame('foobar', $container[static::SERVICE]);
    }

    /**
     * @return void
     */
    public function testArrayAccessSetAddsServiceAsShared(): void
    {
        //Arrange
        $container = new Container();
        $container[static::SERVICE] = $container->share(function () {
            return new class {
            };
        });

        //Act
        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

        //Assert
        $this->assertSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testArrayAccessGetReturnsService(): void
    {
        $container = new Container();
        $container[static::SERVICE] = function () {
            return static::SERVICE;
        };

        $this->assertSame(static::SERVICE, $container[static::SERVICE]);
    }

    /**
     * @return void
     */
    public function testArrayAccessExistsReturnTrueWhenServiceExists(): void
    {
        $container = new Container();
        $container[static::SERVICE] = function () {
        };

        $this->assertTrue(isset($container[static::SERVICE]));
    }

    /**
     * @return void
     */
    public function testArrayAccessExistsReturnFalseWhenServiceNotExists(): void
    {
        $container = new Container();

        $this->assertFalse(isset($container[static::SERVICE]));
    }

    /**
     * @return void
     */
    public function testArrayAccessUnsetRemovesService(): void
    {
        $container = new Container();
        $container[static::SERVICE] = function () {
        };
        unset($container[static::SERVICE]);

        $this->assertFalse(isset($container[static::SERVICE]));
    }

    /**
     * @return void
     */
    public function testDeprecatedShareReturnsCallable(): void
    {
        $container = new Container();
        $service = function () {
        };

        $this->assertSame($service, $container->share($service));
    }
}
