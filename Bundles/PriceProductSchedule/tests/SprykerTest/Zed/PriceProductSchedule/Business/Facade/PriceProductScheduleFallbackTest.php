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
use SprykerTest\Shared\PriceProductSchedule\Helper\PriceProductScheduleDataHelper;

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
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testScheduledDateRangeIsOver()
    {
        $productConcreteTransfer = $this->tester->haveProduct();
        $priceTypeTransfer = (new PriceTypeTransfer())
            ->setIdPriceType(PriceProductScheduleDataHelper::PRICE_TYPE_ID)
            ->setName('test');

        $priceProductOverride = [
            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
        ];

        $this->tester->havePriceProduct(array_merge($priceProductOverride, $this->getPriceProductOverrideData($productConcreteTransfer)));

        $activeTo = new DateTime();
        $activeTo->modify('-1 hour');
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        $priceProductScheduleFacade = $this->tester->getFacade();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setStoreName($this->tester->getLocator()->store()->facade()->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());
        $priceProductTransfer = $this->tester->getLocator()->priceProduct()->facade()->findPriceProductFor($priceProductFilterTransfer);
        $this->assertEquals(PriceProductScheduleDataHelper::PRICE_TYPE_ID, $priceProductTransfer->getFkPriceType());
    }

    /**
     * @return void
     */
    public function testProductHasNoPriceForFallbackPriceType()
    {
        $productConcreteTransfer = $this->tester->haveProduct();

        $activeTo = new DateTime();
        $activeTo->modify('-1 hour');
        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::ACTIVE_TO => $activeTo,
            PriceProductScheduleTransfer::IS_CURRENT => true,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
            ],
        ]);

        $priceProductScheduleFacade = $this->getFacadeMock();
        $priceProductScheduleFacade->applyScheduledPrices();

        $priceProductScheduleEntity = $this->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertFalse($priceProductScheduleEntity->isCurrent());

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setStoreName($this->tester->getLocator()->store()->facade()->getCurrentStore()->getName())
            ->setCurrency($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());

        $priceProductTransfer = $this->tester->getLocator()->priceProduct()->facade()->findPriceProductFor($priceProductFilterTransfer);
        $this->assertNull(PriceProductScheduleDataHelper::PRICE_TYPE_ID, $priceProductTransfer);
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
}
