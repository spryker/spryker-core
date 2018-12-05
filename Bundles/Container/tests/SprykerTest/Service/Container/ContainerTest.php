<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Container;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\Exception\ContainerException;
use Spryker\Service\Container\Exception\FrozenServiceException;
use Spryker\Service\Container\Exception\NotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Container
 * @group ContainerTest
 * Add your own group annotations below this line
 */
class ContainerTest extends Unit
{
    /**
     * @return void
     */
    protected const SERVICE = 'service';

    /**
     * @return void
     */
    public function testSetAddsServiceOnConstruction(): void
    {
        $service = function () {
            return static::SERVICE;
        };

        $container = new Container([
            static::SERVICE => $service,
        ]);

        $this->assertSame(static::SERVICE, $container->get(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testSetAddsService(): void
    {
        $container = new Container();
        $service = function () {
            return static::SERVICE;
        };
        $container->set(static::SERVICE, $service);

        $this->assertSame(static::SERVICE, $container->get(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testSetThrowsAnExceptionWhenServiceToBeAddedAlreadyExistsAndWasAlreadyRequested(): void
    {
        $this->expectException(FrozenServiceException::class);

        $container = new Container();
        $service = function () {
            return static::SERVICE;
        };
        $container->set(static::SERVICE, $service);
        $container->get(static::SERVICE);
        $container->set(static::SERVICE, $service);
    }

    /**
     * @return void
     */
    public function testGetReturnsSameServiceInstanceOnConsecutiveCalls(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, function () {
            return new class {
            };
        });
        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

        $this->assertSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testGetReturnsAlwaysANewServiceInstanceWhenServiceMarkedAsFactory(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $container->factory(function () {
            return new class {
            };
        }));

        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

        $this->assertNotSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testGetThrowsAnExceptionWHenRequestedServiceNotExists(): void
    {
        $this->expectException(NotFoundException::class);

        $container = new Container();
        $container->get(static::SERVICE);
    }

    /**
     * @return void
     */
    public function testHasReturnsFalseWhenServiceWasNotAdded(): void
    {
        $container = new Container();
        $this->assertFalse($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testHasReturnsTrueWhenServiceWasAdded(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, function () {
        });

        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testRemoveRemovesAService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, function () {
        });
        $container->remove(static::SERVICE);

        $this->assertFalse($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testFactoryThrowsAnExceptionWhenPassedServiceNotInvokable(): void
    {
        $this->expectException(ContainerException::class);

        $container = new Container();
        $container->factory('');
    }

    /**
     * @return void
     */
    public function testProtectThrowsAnExceptionWhenPassedServiceNotInvokable(): void
    {
        $this->expectException(ContainerException::class);

        $container = new Container();
        $container->protect('');
    }

    /**
     * @return void
     */
    public function testProtectServiceIsNotResolved(): void
    {
        $container = new Container();
        $service = function () {
            return static::SERVICE;
        };
        $container->set(static::SERVICE, $container->protect($service));

        $this->assertSame($service, $container->get(static::SERVICE));
    }

    /***************************************************************************************
     * F O L L O W I N G T E S T S A R E O N L Y P R E S E N T F O R B C R E A S O N S ! ! *
     ***************************************************************************************/

    /**
     * @return void
     */
    public function testArrayAccessSetAddsService(): void
    {
        $container = new Container();
        $container[static::SERVICE] = function () {
        };

        $this->assertTrue($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testArrayAccessSetAddsServiceAsFactory(): void
    {
        $container = new Container();
        $container[static::SERVICE] = function () {
            return new class {
            };
        };

        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

        $this->assertNotSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testArrayAccessSetAddsServiceAsShared(): void
    {
        $container = new Container();
        $container[static::SERVICE] = $container->share(function () {
            return new class {
            };
        });

        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

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
