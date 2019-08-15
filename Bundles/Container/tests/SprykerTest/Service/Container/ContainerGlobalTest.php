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
 * @group ContainerGlobalTest
 * Add your own group annotations below this line
 */
class ContainerGlobalTest extends Unit
{
    protected const SERVICE = 'service';
    protected const SERVICE_GLOBAL = 'global service';

    protected const SERVICE_PROPERTY_1 = 'SERVICE_PROPERTY_1';
    protected const SERVICE_PROPERTY_2 = 'SERVICE_PROPERTY_2';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->remove(static::SERVICE_GLOBAL);
        $container->remove(static::SERVICE);
    }

    /**
     * @return void
     */
    public function testSetGlobalAddsAService(): void
    {
        $container = new Container();
        $service = function () {
            return static::SERVICE_GLOBAL;
        };
        $container->setGlobal(static::SERVICE_GLOBAL, $service);

        $this->assertSame(static::SERVICE_GLOBAL, $container->get(static::SERVICE_GLOBAL));
    }

    /**
     * @return void
     */
    public function testSetGlobalAddsAServiceAndMakesItGloballyAvailable(): void
    {
        $container = new Container();
        $service = function () {
            return static::SERVICE_GLOBAL;
        };
        $container->setGlobal(static::SERVICE_GLOBAL, $service);

        $container = new Container();

        $this->assertSame(static::SERVICE_GLOBAL, $container->get(static::SERVICE_GLOBAL));
    }

    /**
     * @return void
     */
    public function testSetGlobalThrowsAnExceptionWhenServiceWasAlreadyRetrieved(): void
    {
        $container = new Container();
        $service = function () {
            return static::SERVICE_GLOBAL;
        };
        $container->setGlobal(static::SERVICE_GLOBAL, $service);
        $container->get(static::SERVICE_GLOBAL);

        $this->expectException(FrozenServiceException::class);

        $container->setGlobal(static::SERVICE_GLOBAL, $service);
    }

    /**
     * @return void
     */
    public function testGetGlobalReturnsSameServiceInstanceOnConsecutiveCalls(): void
    {
        $container = new Container();
        $container->setGlobal(static::SERVICE_GLOBAL, function () {
            return new class {
            };
        });

        $service = $container->get(static::SERVICE_GLOBAL);
        $service2 = $container->get(static::SERVICE_GLOBAL);

        $this->assertSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testHasReturnsFalseWhenGlobalServiceIsNotSet(): void
    {
        $container = new Container();

        $this->assertFalse($container->has(static::SERVICE_GLOBAL), 'Service was found in the Container although it should not be in there.');
    }

    /**
     * @return void
     */
    public function testHasReturnsTrueWhenGlobalServiceIsSet(): void
    {
        $container = new Container();
        $container->setGlobal(static::SERVICE_GLOBAL, function () {
            return static::SERVICE_GLOBAL;
        });

        $container = new Container();

        $this->assertTrue($container->has(static::SERVICE_GLOBAL), 'Service was not found in the Container although it should be in there.');
    }

    /**
     * @return void
     */
    public function testRemoveRemovesAGlobalService(): void
    {
        $container = new Container();
        $container->setGlobal(static::SERVICE_GLOBAL, function () {
        });

        $container->remove(static::SERVICE_GLOBAL);

        $this->assertFalse($container->has(static::SERVICE_GLOBAL));
    }

    /**
     * @return void
     */
    public function testConfigureWillThrowAnExceptionIfServiceIsNotSet(): void
    {
        $this->expectException(NotFoundException::class);

        $container = new Container();
        $container->configure(static::SERVICE, []);
    }

    /**
     * @return void
     */
    public function testConfigureWithIsGlobalTrueWillMakeServiceGloballyAvailable(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, function () {
            return static::SERVICE;
        });
        $container->configure(static::SERVICE, ['isGlobal' => true]);

        $container = new Container();
        $this->assertTrue($container->has(static::SERVICE), 'Expected that a normal added service can be made globally available but service not found in new Container');
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenPassedServiceIsNotAService(): void
    {
        $container = new Container();
        $container->setGlobal(static::SERVICE_GLOBAL, function () {
            return [static::SERVICE_PROPERTY_1 => true];
        });

        $this->expectException(ContainerException::class);
        $container->extend(static::SERVICE_GLOBAL, 'not a service');
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenTheServiceToBeExtendedIsFrozen(): void
    {
        $container = new Container();
        $container->setGlobal(static::SERVICE_GLOBAL, function () {
            return [static::SERVICE_PROPERTY_1 => true];
        });

        $container->get(static::SERVICE_GLOBAL);

        $this->expectException(FrozenServiceException::class);
        $container->extend(static::SERVICE_GLOBAL, function () {
        });
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenTheServiceToBeExtendedIsNotAnObjectOrNotInvokable(): void
    {
        $container = new Container();
        $container->setGlobal(static::SERVICE_GLOBAL, 'not an object and not invokable');

        $container->get(static::SERVICE_GLOBAL);

        $this->expectException(ContainerException::class);
        $container->extend(static::SERVICE_GLOBAL, function () {
        });
    }

    /**
     * @return void
     */
    public function testExtendExtendsAGlobalService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE_GLOBAL, function () {
            return 'foo';
        });
        $container->configure(static::SERVICE_GLOBAL, ['isGlobal' => true]);

        $container->extend(static::SERVICE_GLOBAL, function ($existingService) {
            return $existingService . 'bar';
        });

        $this->assertSame('foobar', $container->get(static::SERVICE_GLOBAL));
    }
}
