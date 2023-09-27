<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use SprykerTest\Zed\ServicePointsRestApi\ServicePointsRestApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointsRestApi
 * @group Business
 * @group Facade
 * @group ServicePointsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ServicePointsRestApiFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID = 'TEST_SERVICE_POINT_UUID';

    /**
     * @var string
     */
    protected const TEST_ITEM_GROUP_KEY_1 = 'TEST_ITEM_GROUP_KEY_1';

    /**
     * @var string
     */
    protected const TEST_ITEM_GROUP_KEY_2 = 'TEST_ITEM_GROUP_KEY_2';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\ServicePointsRestApi\ServicePointsRestApiBusinessTester
     */
    protected ServicePointsRestApiBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty(SpyServicePointQuery::create());
    }

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithAvailableServicePointsExpandsCheckoutDataWithServicePoints(): void
    {
        // Assign
        $servicePointTransfer = (new ServicePointTransfer())->setUuid(static::TEST_SERVICE_POINT_UUID);
        $quoteTransfer = (new QuoteBuilder())->build()->addItem(
            (new ItemBuilder())->build()->setServicePoint($servicePointTransfer),
        );

        // Act
        $restCheckoutDataTransfer = $this->tester->getFacade()->expandCheckoutDataWithAvailableServicePoints(
            (new RestCheckoutDataTransfer())->setQuote($quoteTransfer),
            new RestCheckoutRequestAttributesTransfer(),
        );

        // Assert
        $this->assertEquals(1, $restCheckoutDataTransfer->getServicePoints()->count());
        $this->assertEquals(
            $servicePointTransfer->getUuid(),
            $restCheckoutDataTransfer->getServicePoints()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithAvailableServicePointsExpandsCheckoutDataWithPresentServicePoints(): void
    {
        // Assign
        $servicePointTransfer = (new ServicePointTransfer())->setUuid(static::TEST_SERVICE_POINT_UUID);
        $quoteTransfer = (new QuoteBuilder())->build()
            ->addItem((new ItemBuilder())->build()->setServicePoint($servicePointTransfer))
            ->addItem((new ItemBuilder())->build());

        // Act
        $restCheckoutDataTransfer = $this->tester->getFacade()->expandCheckoutDataWithAvailableServicePoints(
            (new RestCheckoutDataTransfer())->setQuote($quoteTransfer),
            new RestCheckoutRequestAttributesTransfer(),
        );

        // Assert
        $this->assertEquals(1, $restCheckoutDataTransfer->getServicePoints()->count());
        $this->assertEquals(
            $servicePointTransfer->getUuid(),
            $restCheckoutDataTransfer->getServicePoints()->getIterator()->current()->getUuid(),
        );
    }

    /**
     * @return void
     */
    public function testExpandCheckoutDataWithAvailableServicePointsDoesntExpandCheckoutDataTransferIfQuoteItemsAreNotProvided(): void
    {
        // Act
        $restCheckoutDataTransfer = $this->tester->getFacade()->expandCheckoutDataWithAvailableServicePoints(
            (new RestCheckoutDataTransfer())->setQuote(new QuoteTransfer()),
            new RestCheckoutRequestAttributesTransfer(),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataTransfer->getServicePoints()->count());
    }

    /**
     * @dataProvider mapServicePointToQuoteItemMapsServicePointTransfersToItemTransfersDataProvider
     *
     * @param bool $isServicePointActive
     * @param bool $withStoreRelation
     *
     * @return void
     */
    public function testMapServicePointToQuoteItemMapsServicePointTransfersToItemTransfers(
        bool $isServicePointActive,
        bool $withStoreRelation
    ): void {
        // Assign
        $storeTransfer = $withStoreRelation ? $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_NAME_DE]) : null;

        $quoteTransfer = $this->tester->createQuoteTransferWithItems(
            [static::TEST_ITEM_GROUP_KEY_1, static::TEST_ITEM_GROUP_KEY_2],
            $storeTransfer,
        );

        $servicePointTransfer = $this->tester->createServicePoint($isServicePointActive, $storeTransfer);
        $restServicePointTransfer = $this->tester->createRestServicePointTransfer(
            $servicePointTransfer,
            [static::TEST_ITEM_GROUP_KEY_1, static::TEST_ITEM_GROUP_KEY_2],
        );
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransfer(
            [$restServicePointTransfer],
        );

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapServicePointToQuoteItem(
            $restCheckoutRequestAttributesTransfer,
            $quoteTransfer,
        );

        // Assert
        $itemTransfers = $quoteTransfer->getItems()->getArrayCopy();
        $this->assertEquals(2, $quoteTransfer->getItems()->count());
        $this->assertEquals($servicePointTransfer->getUuid(), $itemTransfers[0]->getServicePoint()->getUuid());
        $this->assertEquals($servicePointTransfer->getUuid(), $itemTransfers[1]->getServicePoint()->getUuid());
    }

    /**
     * @return array<string, list<bool>>
     */
    public function mapServicePointToQuoteItemMapsServicePointTransfersToItemTransfersDataProvider(): array
    {
        return [
            'Active service point with store relation' => [true, true],
            'Active service point without store relation' => [true, false],
            'Inactive service point with store relation' => [false, true],
            'Inactive service point without store relation' => [false, false],
        ];
    }
}
