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
 *
 * @group SprykerTest
 * @group Service
 * @group Container
 * @group ContainerTest
 * Add your own group annotations below this line
 */
class ContainerTest extends Unit
{
    protected const SERVICE = 'service';
    protected const SERVICE_GLOBAL = 'global service';
    protected const SERVICE_PROPERTY_1 = 'SERVICE_PROPERTY_1';
    protected const SERVICE_PROPERTY_2 = 'SERVICE_PROPERTY_2';

    /**
     * @var bool
     */
    protected $errorIsTriggered = false;

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
    public function testSetAddsServiceOnConstruction(): void
    {
        //Arrange
        $service = function () {
            return static::SERVICE;
        };

        //Act
        $container = new Container([
            static::SERVICE => $service,
        ]);

        //Assert
        $this->assertSame(static::SERVICE, $container->get(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testSetAddsService(): void
    {
        //Arrange
        $container = new Container();
        $service = function () {
            return static::SERVICE;
        };

        //Act
        $container->set(static::SERVICE, $service);

        //Assert
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
        //Arrange
        $container = new Container();
        $container->set(static::SERVICE, function () {
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
    public function testGetReturnsAlwaysANewServiceInstanceWhenServiceMarkedAsFactory(): void
    {
        //Arrange
        $container = new Container();
        $container->set(static::SERVICE, $container->factory(function () {
            return new class {
            };
        }));

        //Act
        $service = $container->get(static::SERVICE);
        $service2 = $container->get(static::SERVICE);

        //Assert
        $this->assertNotSame($service, $service2);
    }

    /**
     * @return void
     */
    public function testGetThrowsAnExceptionWhenRequestedServiceNotExists(): void
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
        //Arrange
        $container = new Container();
        $container->set(static::SERVICE, function () {
        });

        //Act
        $container->remove(static::SERVICE);

        //Assert
        $this->assertFalse($container->has(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testRemoveWithNotExistingServiceIdentifier(): void
    {
        $container = new Container();
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
    public function testProtectedServiceIsNotResolved(): void
    {
        //Arrange
        $container = new Container();
        $service = function () {
            return static::SERVICE;
        };

        //Act
        $container->set(static::SERVICE, $container->protect($service));

        //Assert
        $this->assertSame($service, $container->get(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenPassedServiceIsNotAService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, function () {
            return [static::SERVICE_PROPERTY_1 => true];
        });

        $this->expectException(ContainerException::class);
        $container->extend(static::SERVICE, 'not a service');
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenTheServiceToBeExtendedIsFrozen(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, function () {
            return [static::SERVICE_PROPERTY_1 => true];
        });

        $container->get(static::SERVICE);

        $this->expectException(FrozenServiceException::class);
        $container->extend(static::SERVICE, function () {
        });
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenTheServiceToBeExtendedIsNotAnObjectOrNotInvokable(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, 'not an object and not invokable');

        $this->expectException(ContainerException::class);
        $container->extend(static::SERVICE, function () {
        });
    }

    /**
     * @return void
     */
    public function testExtendThrowsExceptionWhenTheServiceToBeExtendedIsProtected(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $container->protect(function () {
        }));

        $this->expectException(ContainerException::class);
        $container->extend(static::SERVICE, function () {
        });
    }

    /**
     * @return void
     */
    public function testExtendAFactoryServiceWillReplaceTheServiceInTheFactoryCollection(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $container->factory(function () {
            return 'foo';
        }));
        $container->extend(static::SERVICE, function ($service) {
            return $service . 'bar';
        });

        $this->assertSame('foobar', $container->get(static::SERVICE));
    }

    /**
     * @return void
     */
    public function testExtendExtendsServiceWithProperty(): void
    {
        //Arrange
        $container = new Container();
        $container->set(static::SERVICE, function () {
            return [static::SERVICE_PROPERTY_1 => true];
        });

        //Act
        $container->extend(static::SERVICE, function ($existingService, $container) {
            $existingService[static::SERVICE_PROPERTY_2] = true;

            return $existingService;
        });

        //Assert
        $this->assertSame(
            [static::SERVICE_PROPERTY_1 => true, static::SERVICE_PROPERTY_2 => true],
            $container->get(static::SERVICE)
        );
    }

    /**
     * @return void
     */
    public function testExtendExtendsServiceWithPropertyAfterItWasDefined(): void
    {
        //Arrange
        $container = new Container();
        $container->extend(static::SERVICE, function ($existingService, $container) {
            $existingService[static::SERVICE_PROPERTY_2] = true;

            return $existingService;
        });

        //Act
        $container->set(static::SERVICE, function () {
            return [static::SERVICE_PROPERTY_1 => true];
        });

        //Assert
        $this->assertSame(
            [static::SERVICE_PROPERTY_1 => true, static::SERVICE_PROPERTY_2 => true],
            $container->get(static::SERVICE)
        );
    }

    /**
     * @return void
     */
    public function testTriggerErrorIsNotExecuted(): void
    {
        $container = new Container();
        $container->set(Container::TRIGGER_ERROR, false);

        $previousErrorHandler = set_error_handler([$this, 'setErrorTriggered']);
        $container[static::SERVICE] = 'foo';
        $this->assertFalse($this->errorIsTriggered, 'Deprecation message should not be shown');
        set_error_handler($previousErrorHandler);
        $this->errorIsTriggered = false;
    }

    /**
     * @return void
     */
    public function testTriggerErrorIsExecutedWhenEnabled(): void
    {
        $container = new Container();
        $container->set(Container::TRIGGER_ERROR, true);

        $previousErrorHandler = set_error_handler([$this, 'setErrorTriggered']);
        $container[static::SERVICE] = 'foo';

        $this->assertTrue($this->errorIsTriggered, 'Deprecation message should not be shown');

        set_error_handler($previousErrorHandler);
        $this->errorIsTriggered = false;
    }

    /**
     * @return void
     */
    public function setErrorTriggered(): void
    {
        $this->errorIsTriggered = true;
    }
}
