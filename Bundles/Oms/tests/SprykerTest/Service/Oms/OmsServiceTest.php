<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Oms;

use Codeception\Test\Unit;
use Spryker\Service\Oms\OmsConfig;
use Spryker\Service\Oms\OmsServiceFactory;
use Spryker\Service\Oms\OmsServiceInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Oms
 * @group OmsServiceTest
 * Add your own group annotations below this line
 */
class OmsServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\Oms\OmsServiceTester
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
     * @return \Spryker\Service\Oms\OmsServiceInterface
     */
    protected function configureService(int $roundMode): OmsServiceInterface
    {
        /**
         * @var \Spryker\Service\Oms\OmsService $service
         */
        $service = $this->tester->getLocator()->oms()->service();
        $serviceFactory = new OmsServiceFactory();
        $serviceConfigMock = $this->getMockBuilder(OmsConfig::class)
            ->setMethods(['getRoundPrecision', 'getRoundMode'])
            ->getMock();
        $serviceConfigMock->method('getRoundPrecision')->willReturn(2);
        $serviceConfigMock->method('getRoundMode')->willReturn($roundMode);
        $serviceFactory->setConfig($serviceConfigMock);
        $service->setFactory($serviceFactory);
        return $service;
    }
}
