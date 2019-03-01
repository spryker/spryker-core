<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Container;

use Closure;
use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\Exception\AliasException;
use Spryker\Service\Container\Exception\NotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Container
 * @group ContainerAliasTest
 * Add your own group annotations below this line
 */
class ContainerAliasTest extends Unit
{
    protected const SERVICE = 'service';
    protected const SERVICE_2 = 'service-2';

    protected const SERVICE_ALIAS = 'service-alias';
    protected const SERVICE_ALIAS_2 = 'service-alias-2';

    protected const SERVICE_GLOBAL = 'global service';

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $container = new Container();
        $container->remove(static::SERVICE_GLOBAL);
        $container->remove(static::SERVICE);
        $container->remove(static::SERVICE_2);
        $container->remove(static::SERVICE_ALIAS);
        $container->remove(static::SERVICE_ALIAS_2);
    }

    /**
     * @return void
     */
    public function testConfigureCanAddAnAliasForAService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $this->createServiceClosure());
        $container->configure(static::SERVICE, ['alias' => static::SERVICE_ALIAS]);

        $this->assertSame(static::SERVICE, $container->get(static::SERVICE_ALIAS));
    }

    /**
     * @return void
     */
    public function testConfigureCanAddAliasesForAService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $this->createServiceClosure());
        $container->configure(static::SERVICE, [
            'alias' => [
                static::SERVICE_ALIAS,
                static::SERVICE_ALIAS_2,
            ],
        ]);

        $this->assertSame(static::SERVICE, $container->get(static::SERVICE_ALIAS_2));
    }

    /**
     * @return void
     */
    public function testConfigureAnAliasWhichIsAlreadyUsedForAnotherServiceWillThrowAnException(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $this->createServiceClosure());
        $container->configure(static::SERVICE, ['alias' => static::SERVICE_ALIAS]);

        $container->set(static::SERVICE_2, $this->createServiceClosure(static::SERVICE_2));

        $this->expectException(AliasException::class);
        $container->configure(static::SERVICE_2, ['alias' => static::SERVICE_ALIAS]);
    }

    /**
     * @return void
     */
    public function testConfigureCanAddAnAliasForAGlobalService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE_GLOBAL, $this->createServiceClosure(static::SERVICE_GLOBAL));
        $container->configure(static::SERVICE_GLOBAL, [
            'isGlobal' => true,
            'alias' => static::SERVICE_ALIAS,
        ]);

        $container = new Container();

        $this->assertSame(static::SERVICE_GLOBAL, $container->get(static::SERVICE_ALIAS));
    }

    /**
     * @return void
     */
    public function testHasReturnsTrueForAnAliasedServiceIdentifierIfItExists(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $this->createServiceClosure());
        $container->configure(static::SERVICE, ['alias' => static::SERVICE_ALIAS]);

        $this->assertTrue($container->has(static::SERVICE_ALIAS));
    }

    /**
     * @return void
     */
    public function testRemoveAServiceAlsoRemovesAllAliasesForTheService(): void
    {
        $container = new Container();
        $container->set(static::SERVICE, $this->createServiceClosure(static::SERVICE));
        $container->configure(static::SERVICE, ['alias' => static::SERVICE_ALIAS]);

        $container->remove(static::SERVICE);

        $this->assertFalse($container->has(static::SERVICE));
        $this->assertFalse($container->has(static::SERVICE_ALIAS));

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('The requested service "%s" was not found in the container!', static::SERVICE_ALIAS));

        $container->get(static::SERVICE_ALIAS);
    }

    /**
     * @param string $returnValue
     *
     * @return \Closure
     */
    protected function createServiceClosure(string $returnValue = self::SERVICE): Closure
    {
        $service = function () use ($returnValue) {
            return $returnValue;
        };

        return $service;
    }
}
