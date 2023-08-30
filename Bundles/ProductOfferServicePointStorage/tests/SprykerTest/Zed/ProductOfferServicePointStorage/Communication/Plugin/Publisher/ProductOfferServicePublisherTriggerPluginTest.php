<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\Communication\Plugin\Publisher\ProductOfferServicePublisherTriggerPlugin;
use SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group ProductOfferServicePublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class ProductOfferServicePublisherTriggerPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE = 'PRODUCT_OFFER_REFERENCE';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_2 = 'PRODUCT_OFFER_REFERENCE_2';

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageCommunicationTester
     */
    protected ProductOfferServicePointStorageCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceTableAndRelationsAreEmpty();
    }

    /**
     * @dataProvider getProductOfferServicePublisherGetDataProvider
     *
     * @param list<array<string, mixed>> $productOffersData
     * @param int $offset
     * @param int $limit
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetDataShouldReturnDataByOffsetAndLimit(
        array $productOffersData,
        int $offset,
        int $limit,
        int $expectedCount
    ): void {
        // Arrange
        foreach ($productOffersData as $productOfferData) {
            $this->tester->haveProductOfferService([
                ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $this->tester->haveProductOffer($productOfferData)->getIdProductOfferOrFail(),
                ProductOfferServiceTransfer::ID_SERVICE => $this->tester->haveService()->getIdServiceOrFail(),
            ]);
        }

        // Act
        $data = (new ProductOfferServicePublisherTriggerPlugin())->getData($offset, $limit);

        // Assert
        $this->assertCount($expectedCount, $data);
    }

    /**
     * @return array<string, array<list<array<string, mixed>>|int>>
     */
    protected function getProductOfferServicePublisherGetDataProvider(): array
    {
        return [
            'Should return empty collection when product offer services do not exist' => [
                [], 0, 1, 0,
            ],
            'Should return empty collection when offset is higher then number of product offer services' => [
                [[ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE]], 1, 1, 0,
            ],
            'Should return empty collection when 0 limit is provided' => [
                [[ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE]], 0, 0, 0,
            ],
            'Should return collection when correct limit is provided' => [
                [[ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE]], 0, 1, 1,
            ],
            'Should return data when correct offset is provided' => [
                [
                    [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE],
                    [ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE_2],
                ], 1, 1, 1,
            ],
        ];
    }
}
