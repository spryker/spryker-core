<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Offer;

use Codeception\Test\Unit;
use Spryker\Service\Offer\OfferConfig;
use Spryker\Service\Offer\OfferServiceFactory;
use Spryker\Service\Offer\OfferServiceInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group Offer
 * @group OfferServiceTest
 * Add your own group annotations below this line
 */
class OfferServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\Offer\OfferServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testShouldRoundToUpFraction(): void
    {
        $service = $this->configureService(PHP_ROUND_HALF_UP);
        $this->assertEquals(2, $service->convert(2.35));
    }

    /**
     * @param int $roundMode
     *
     * @return \Spryker\Service\Offer\OfferServiceInterface
     */
    protected function configureService(int $roundMode): OfferServiceInterface
    {
        /**
         * @var \Spryker\Service\Offer\OfferService $service
         */
        $service = $this->tester->getLocator()->offer()->service();
        $serviceFactory = new OfferServiceFactory();
        $serviceConfigMock = $this->getMockBuilder(OfferConfig::class)
            ->setMethods(['getRoundPrecision', 'getRoundMode'])
            ->getMock();
        $serviceConfigMock->method('getRoundPrecision')->willReturn(2);
        $serviceConfigMock->method('getRoundMode')->willReturn($roundMode);
        $serviceFactory->setConfig($serviceConfigMock);
        $service->setFactory($serviceFactory);
        return $service;
    }
}
