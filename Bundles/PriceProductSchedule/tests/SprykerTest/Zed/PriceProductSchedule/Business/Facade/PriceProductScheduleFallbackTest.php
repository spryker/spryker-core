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
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;
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
    public const PRICE_TYPE_NAME_ORIGINAL = PriceProductScheduleConfig::PRICE_TYPE_ORIGINAL;

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected $spyPriceProductScheduleQuery;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductFacade = $this->tester->getLocator()->priceProduct()->facade();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
        $this->spyPriceProductScheduleQuery = $this->tester->getPriceProductScheduleQuery();
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRevertedAfterPriceProductScheduleIsOver(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setIdPriceType(static::PRICE_TYPE_ID)
            ->setName(static::PRICE_TYPE_NAME_ORIGINAL);

        $priceProductOverride = [
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
        ];

        $this->tester->havePriceProduct(array_merge($priceProductOverride, $this->getPriceProductOverrideData($productConcreteTransfer)));

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                ],
            ],
        ]);

        // Act
        $facade = $this->tester->getFacade();
        $facade->setFactory((new PriceProductScheduleBusinessFactory())->setConfig($this->getConfigMock()));
        $facade->applyScheduledPrices();

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setStoreName($this->storeFacade->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);
        $this->assertEquals(static::DEFAULT_PRICE_TYPE_ID, $priceProductTransfer->getFkPriceType(), 'Product price type should be reverted after scheduled price is over.');
    }

    /**
     * @return void
     */
    public function testProductPriceShouldBeRemovedIfFallbackPriceTypeNotConfigured(): void
    {
        // Assign
        $productConcreteTransfer = $this->tester->haveProduct();

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('-1 hour')),
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-4 days')),
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $this->priceProductFacade->getDefaultPriceTypeName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $this->tester->getPriceTypeId($this->priceProductFacade->getDefaultPriceTypeName()),
                ],
            ],
        ]);

        // Act
        $facade = $this->tester->getFacade();
        $facade->setFactory((new PriceProductScheduleBusinessFactory())->setConfig($this->getNotConfiguredConfigMock()));
        $facade->applyScheduledPrices();

        // Assert
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setStoreName($this->storeFacade->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);
        $this->assertNull($priceProductTransfer, 'Product price type should be removed after scheduled price is over if no fallback price os configured.');
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
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    protected function getPriceProductQuery(): SpyPriceProductQuery
    {
        return new SpyPriceProductQuery();
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
