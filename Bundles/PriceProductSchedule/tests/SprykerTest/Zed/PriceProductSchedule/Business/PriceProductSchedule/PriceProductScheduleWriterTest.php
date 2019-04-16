<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group PriceProductSchedule
 * @group PriceProductScheduleWriterTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleWriterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleBusinessFactory
     */
    protected $priceProductScheduleFactory;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface
     */
    protected $priceProductScheduleWriter;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->priceProductScheduleFactory = new PriceProductScheduleBusinessFactory();
        $this->priceProductScheduleWriter = $this->priceProductScheduleFactory->createPriceProductScheduleWriter();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return void
     */
    public function testSavePriceProductScheduleShouldSavePriceProductSchedule(): void
    {
        // Assign

        $priceProductScheduleData[PriceProductScheduleTransfer::PRICE_PRODUCT] = $this->getPriceProductData();
        $priceProductScheduleData[PriceProductScheduleTransfer::ACTIVE_FROM] = (new DateTime('-30 days'));
        $priceProductScheduleData[PriceProductScheduleTransfer::ACTIVE_TO] = (new DateTime('+30 days'));

        $priceProductScheduleTransfer = $this->tester->havePriceProductSchedule($priceProductScheduleData);

        $priceType = $this->tester->havePriceType();
        $priceProductScheduleTransfer->getPriceProduct()->setPriceType($priceType);

        // Act
        $this->priceProductScheduleWriter->savePriceProductSchedule($priceProductScheduleTransfer);

        // Assert
        $priceProductScheduleEntity = $this->tester->getPriceProductScheduleQuery()->findOneByIdPriceProductSchedule($priceProductScheduleTransfer->getIdPriceProductSchedule());
        $this->assertEquals($priceProductScheduleEntity->getFkPriceType(), $priceType->getIdPriceType());
    }

    /**
     * @return array
     */
    protected function getPriceProductData(): array
    {
        $currencyId = $this->tester->haveCurrency();
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $priceType = $this->tester->havePriceType();
        $productConcreteTransfer = $this->tester->haveProduct();

        return [
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE => [
                PriceTypeTransfer::NAME => $priceType->getName(),
                PriceTypeTransfer::ID_PRICE_TYPE => $priceType->getIdPriceType(),
            ],
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                MoneyValueTransfer::FK_CURRENCY => $currencyId,
            ],
        ];
    }
}
