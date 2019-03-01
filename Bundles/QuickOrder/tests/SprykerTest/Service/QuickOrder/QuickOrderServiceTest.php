<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\QuickOrder;

use Codeception\Test\Unit;
use Spryker\Service\QuickOrder\QuickOrderConfig;
use Spryker\Service\QuickOrder\QuickOrderServiceFactory;
use Spryker\Service\QuickOrder\QuickOrderServiceInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group QuickOrder
 * @group QuickOrderServiceTest
 * Add your own group annotations below this line
 */
class QuickOrderServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\UtilEncoding\UtilEncodingServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testShouldRoundToUpFraction(): void
    {
        $service = $this->configureService(PHP_ROUND_HALF_UP);

        $this->assertEquals(1.78, $service->round(1.775));
    }

    /**
     * @return void
     */
    public function testShouldRoundToDownFraction(): void
    {
        $service = $this->configureService(PHP_ROUND_HALF_DOWN);

        $this->assertEquals(1.77, $service->round(1.775));
    }

    /**
     * @param int $roundMode
     *
     * @return \Spryker\Service\QuickOrder\QuickOrderServiceInterface
     */
    protected function configureService(int $roundMode): QuickOrderServiceInterface
    {
        /**
         * @var \Spryker\Service\QuickOrder\QuickOrderService $service
         */
        $service = $this->tester->getLocator()->quickOrder()->service();
        $serviceFactory = new QuickOrderServiceFactory();
        $serviceConfigMock = $this->getMockBuilder(QuickOrderConfig::class)
            ->setMethods(['getRoundPrecision', 'getRoundMode'])
            ->getMock();
        $serviceConfigMock->method('getRoundPrecision')->willReturn(2);
        $serviceConfigMock->method('getRoundMode')->willReturn($roundMode);
        $serviceFactory->setConfig($serviceConfigMock);
        $service->setFactory($serviceFactory);

        return $service;
    }
}
