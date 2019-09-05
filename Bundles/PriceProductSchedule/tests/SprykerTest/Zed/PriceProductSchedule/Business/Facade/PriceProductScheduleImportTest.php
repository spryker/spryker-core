<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSchedule\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\PriceProductScheduleListBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSchedule
 * @group Business
 * @group Facade
 * @group PriceProductScheduleImportTest
 * Add your own group annotations below this line
 */
class PriceProductScheduleImportTest extends Unit
{
    protected const FORMAT_DATE = 'Y-m-d\TH:i:sO';

    /**
     * @var \SprykerTest\Zed\PriceProductSchedule\PriceProductScheduleBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleFacadeInterface
     */
    protected $priceProductScheduleFacade;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty();

        $this->priceProductScheduleFacade = $this->tester->getFacade();
        $this->storeFacade = $this->tester->getLocator()->store()->facade();
        $this->currencyFacade = $this->tester->getLocator()->currency()->facade();
    }

    /**
     * @return void
     */
    public function testImportPriceProductSchedulesShouldImportValidSchedules(): void
    {
        // Assign
        $count = 10;
        $priceProductScheduleImportTransfers = $this->prepareValidPriceProductScheduleImportData($count);
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();
        $priceProductScheduleImportRequest = (new PriceProductScheduledListImportRequestTransfer())
            ->setPriceProductScheduleList($priceProductScheduleListTransfer)
            ->setItems($priceProductScheduleImportTransfers);

        // Act
        $priceProductScheduleImportResponse = $this->priceProductScheduleFacade
            ->importPriceProductSchedules($priceProductScheduleImportRequest);

        // Assert
        $this->assertTrue(
            $priceProductScheduleImportResponse->getIsSuccess(),
            'Scheduled prices should be imported successfully.'
        );

        $priceProductScheduleEntitiesCount = $this->tester->getPriceProductScheduleQuery()->count();
        $this->assertEquals(
            $count,
            $priceProductScheduleEntitiesCount,
            'Count of imported items must be equal to db rows'
        );
    }

    /**
     * @dataProvider notValidPriceProductScheduleImportDataProvider
     *
     * @param array $priceProductSchedulesImportData
     *
     * @return void
     */
    public function testImportPriceProductSchedulesShouldNotImportNotValidSchedules(
        array $priceProductSchedulesImportData
    ): void {
        // Assign
        $priceProductScheduleImportRequest = $this->havePriceProductScheduledListImportRequest();

        foreach ($priceProductSchedulesImportData as $priceProductScheduleImportData) {
            $priceProductItemTransfer = $this->tester->havePriceProductScheduleImport($priceProductScheduleImportData);

            $priceProductScheduleImportRequest->addItem($priceProductItemTransfer);
        }

        // Act
        $priceProductScheduleImportResponse = $this->priceProductScheduleFacade->importPriceProductSchedules($priceProductScheduleImportRequest);

        // Assert
        $this->assertFalse(
            $priceProductScheduleImportResponse->getIsSuccess(),
            'Scheduled prices should not be imported.'
        );

        $this->assertNotEmpty(
            $priceProductScheduleImportResponse->getErrors(),
            'Errors should be in response'
        );

        $priceProductScheduleEntitiesCount = $this->tester->getPriceProductScheduleQuery()->count();
        $this->assertEquals(
            1,
            $priceProductScheduleEntitiesCount,
            'No rows should be saved into the db except the base one'
        );
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer
     */
    protected function havePriceProductScheduledListImportRequest(): PriceProductScheduledListImportRequestTransfer
    {
        $priceProductScheduleListTransfer = $this->tester->havePriceProductScheduleList();

        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'AT',
        ]);

        $currencyId = $this->tester->haveCurrency([
            CurrencyTransfer::CODE => 'CUR',
        ]);

        $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);

        $priceTypeTransfer = $this->tester->havePriceType([
            PriceTypeTransfer::NAME => 'PRICE_TYPE_NAME',
        ]);

        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::SKU => 'SKU',
        ]);

        $this->tester->havePriceProductSchedule([
            PriceProductScheduleTransfer::PRICE_PRODUCT_SCHEDULE_LIST => $priceProductScheduleListTransfer,
            PriceProductScheduleTransfer::PRICE_PRODUCT => [
                PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
                PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
                PriceProductTransfer::MONEY_VALUE => [
                    MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                    MoneyValueTransfer::FK_STORE => $storeTransfer->getIdStore(),
                    MoneyValueTransfer::GROSS_AMOUNT => 25000,
                    MoneyValueTransfer::NET_AMOUNT => 20000,
                ],
                PriceProductTransfer::PRICE_TYPE_NAME => $priceTypeTransfer->getName(),
                PriceProductTransfer::PRICE_TYPE => [
                    PriceTypeTransfer::NAME => $priceTypeTransfer->getName(),
                    PriceTypeTransfer::ID_PRICE_TYPE => $priceTypeTransfer->getIdPriceType(),
                ],
            ],
            PriceProductScheduleTransfer::ACTIVE_FROM => (new DateTime('-2 days'))
                ->setTime(0, 0, 0, 0)
                ->format(static::FORMAT_DATE),
            PriceProductScheduleTransfer::ACTIVE_TO => (new DateTime('+3 days'))
                ->setTime(0, 0, 0, 0)
                ->format(static::FORMAT_DATE),
        ]);

        return (new PriceProductScheduledListImportRequestTransfer())
            ->setPriceProductScheduleList(
                (new PriceProductScheduleListBuilder())
                    ->build([PriceProductScheduleListTransfer::NAME => $priceProductScheduleListTransfer->getName()])
            );
    }

    /**
     * @param int $itemsCount
     *
     * @return \ArrayObject
     */
    protected function prepareValidPriceProductScheduleImportData(int $itemsCount): ArrayObject
    {
        $priceProductScheduleImportData = new ArrayObject();

        for ($i = 0; $i < $itemsCount; $i++) {
            $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE', StoreTransfer::ID_STORE => 1]);
            $currencyId = $this->tester->haveCurrency();
            $currencyTransfer = $this->currencyFacade->getByIdCurrency($currencyId);
            $priceTypeTransfer = $this->tester->havePriceType();
            $productConcreteTransfer = $this->tester->haveProduct();

            $priceProductScheduleImportData[] = $this->tester->havePriceProductScheduleImport([
                PriceProductScheduleImportTransfer::CURRENCY_CODE => $currencyTransfer->getCode(),
                PriceProductScheduleImportTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
                PriceProductScheduleImportTransfer::PRICE_TYPE_NAME => $priceTypeTransfer->getName(),
                PriceProductScheduleImportTransfer::STORE_NAME => $storeTransfer->getName(),
                PriceProductScheduleImportTransfer::GROSS_AMOUNT => 25000,
                PriceProductScheduleImportTransfer::NET_AMOUNT => 20000,
                PriceProductScheduleImportTransfer::ACTIVE_FROM => (new DateTime('-2 days'))
                    ->setTime(0, 0, 0, 0)
                    ->format(static::FORMAT_DATE),
                PriceProductScheduleImportTransfer::ACTIVE_TO => (new DateTime('+3 days'))
                    ->setTime(0, 0, 0, 0)
                    ->format(static::FORMAT_DATE),
            ]);
        }

        return $priceProductScheduleImportData;
    }

    /**
     * @return array
     */
    public function notValidPriceProductScheduleImportDataProvider(): array
    {
        return [
            'import price product schedule with incorrect abstract product sku' => [
                [
                    [
                        PriceProductScheduleImportTransfer::SKU_PRODUCT_ABSTRACT => 'FOO',
                    ],
                ],
            ],
            'import price product schedule with incorrect product sku' => [
                [
                    [
                        PriceProductScheduleImportTransfer::SKU_PRODUCT => 'BAR',
                    ],
                ],
            ],
            'import price product schedule with incorrect price type' => [
                [
                    [
                        PriceProductScheduleImportTransfer::PRICE_TYPE_NAME => 'BAR',
                    ],
                ],
            ],
            'import price product schedule with incorrect store name' => [
                [
                    [

                        PriceProductScheduleImportTransfer::STORE_NAME => 'BAR',
                    ],
                ],
            ],
            'import price product schedule with incorrect currency name' => [
                [
                    [
                        PriceProductScheduleImportTransfer::CURRENCY_CODE => 'BAR',
                    ],
                ],
            ],
            'import price product schedule with incorrect net amount' => [
                [
                    [
                        PriceProductScheduleImportTransfer::NET_AMOUNT => 1230123.123,
                    ],
                    [
                        PriceProductScheduleImportTransfer::NET_AMOUNT => "BAR",
                    ],
                ],
            ],
            'import price product schedule with incorrect gross amount' => [
                [
                    [
                        PriceProductScheduleImportTransfer::GROSS_AMOUNT => 1230123.123,
                    ],
                    [
                        PriceProductScheduleImportTransfer::GROSS_AMOUNT => "BAR",
                    ],
                ],
            ],
            'import price product schedule with incorrect active from date' => [
                [
                    [
                        PriceProductScheduleImportTransfer::ACTIVE_FROM => 123123,
                    ],
                ],
            ],
            'import price product schedule with incorrect active to date' => [
                [
                    [
                        PriceProductScheduleImportTransfer::ACTIVE_TO => 123123,
                    ],
                ],
            ],
            'import price product schedule with duplicate schedule' => [
                [
                    [
                        PriceProductScheduleImportTransfer::CURRENCY_CODE => 'CUR',
                        PriceProductScheduleImportTransfer::SKU_PRODUCT => 'SKU',
                        PriceProductScheduleImportTransfer::PRICE_TYPE_NAME => 'PRICE_TYPE_NAME',
                        PriceProductScheduleImportTransfer::STORE_NAME => 'AT',
                        PriceProductScheduleImportTransfer::ACTIVE_FROM => (new DateTime('-2 days'))
                            ->setTime(0, 0, 0, 0)
                            ->format(static::FORMAT_DATE),
                        PriceProductScheduleImportTransfer::ACTIVE_TO => (new DateTime('+3 days'))
                            ->setTime(0, 0, 0, 0)
                            ->format(static::FORMAT_DATE),
                        PriceProductScheduleImportTransfer::GROSS_AMOUNT => 25000,
                        PriceProductScheduleImportTransfer::NET_AMOUNT => 20000,
                    ],
                ],
            ],
        ];
    }
}
