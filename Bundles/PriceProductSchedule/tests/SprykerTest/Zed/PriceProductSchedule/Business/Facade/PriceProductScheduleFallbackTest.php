<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group PriceProductScheduleFallbackTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleFallbackTest extends Unit
{
    public const DEFAULT_PRICE_TYPE_ID = 1;
    public const PRICE_TYPE_ID = 2;

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductPriceShouldBeRevertedAfterPriceProductScheduleIsOver()
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setIdPriceType(static::PRICE_TYPE_ID)
            ->setName('ORIGINAL');

        $priceProductOverride = [
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
        ];

        $this->tester->havePriceProduct(array_merge($priceProductOverride, $this->getPriceProductOverrideData($productConcreteTransfer)));

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        // Act
        $this->getFacadeMock()->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setStoreName($this->tester->getLocator()->store()->facade()->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->tester->getLocator()->priceProduct()->facade()->findPriceProductFor($priceProductFilterTransfer);
        $this->assertEquals(static::DEFAULT_PRICE_TYPE_ID, $priceProductTransfer->getFkPriceType());
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRemovedIfFallbackPriceTypeNotConfigured()
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime())->modify('-1 hour'),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        // Act
        $this->getNotConfiguredFacadeMock()->applyScheduledPrices();

        // Assert
        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setStoreName($this->tester->getLocator()->store()->facade()->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->tester->getLocator()->priceProduct()->facade()->findPriceProductFor($priceProductFilterTransfer);
        $this->assertNull(static::DEFAULT_PRICE_TYPE_ID, $priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return array
     */
    protected function getPriceProductOverrideData(ProductConcreteTransfer $productConcreteTransfer): array
    {
        return [
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
        ];
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function getPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return new SpyPriceProductScheduleQuery();
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function getPriceProductQuery(): SpyPriceProductQuery
    {
        return new SpyPriceProductQuery();
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected function getFacadeMock(): PriceProductScheduleFacadeInterface
    {
        /** @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->getFactoryMock());

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory
     */
    protected function getFactoryMock()
    {
        $factoryMock = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['getConfig'])
            ->getMock();

        $factoryMock->method('getConfig')
            ->willReturn($this->getConfigMock());

        return $factoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getConfigMock()
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->setMethods(['findFallbackPriceType'])
            ->getMock();

        $configMock->method('findFallbackPriceType')
            ->willReturn(PriceProductScheduleConfig::PRICE_TYPE_ORIGINAL);

        return $configMock;
    }

    /**
     * @return \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected function getNotConfiguredFacadeMock(): PriceProductScheduleFacadeInterface
    {
        /** @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->getNotConfiguredFactoryMock());

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory
     */
    protected function getNotConfiguredFactoryMock()
    {
        $factoryMock = $this->getMockBuilder(PriceProductScheduleBusinessFactory::class)
            ->setMethods(['getConfig'])
            ->getMock();

        $factoryMock->method('getConfig')
            ->willReturn($this->getNotConfiguredConfigMock());

        return $factoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected function getNotConfiguredConfigMock()
    {
        $configMock = $this->getMockBuilder(PriceProductScheduleConfig::class)
            ->setMethods(['findFallbackPriceType'])
            ->getMock();

        $configMock->method('findFallbackPriceType')
            ->willReturn(null);

        return $configMock;
    }
}
