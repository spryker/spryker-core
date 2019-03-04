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
     * @dataProvider roundProvider
     *
     * @param int $roundMode
     * @param float $expected
     *
     * @return void
     */
    public function testShouldRoundToUpFraction(int $roundMode, float $expected): void
    {
        $service = $this->configureService($roundMode);

        $this->assertEquals($expected, $service->round(1.775));
    }

    /**
     * @return array
     */
    public function roundProvider(): array
    {
        return [
            'half up mode' => [PHP_ROUND_HALF_UP, 1.78],
            'half down mode' => [PHP_ROUND_HALF_DOWN, 1.77],
        ];
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
