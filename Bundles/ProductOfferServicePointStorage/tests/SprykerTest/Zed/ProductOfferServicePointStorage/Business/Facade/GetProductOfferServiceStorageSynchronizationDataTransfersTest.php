<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointStorage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePointStorage
 * @group Business
 * @group Facade
 * @group GetProductOfferServiceStorageSynchronizationDataTransfersTest
 * Add your own group annotations below this line
 */
class GetProductOfferServiceStorageSynchronizationDataTransfersTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_SERVICE_POINT = -1;

    /**
     * @var \SprykerTest\Zed\ProductOfferServicePointStorage\ProductOfferServicePointStorageBusinessTester
     */
    protected ProductOfferServicePointStorageBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceStorageTableAndRelationsAreEmpty();
        $this->tester->setDependencies();
    }

    /**
     * @dataProvider getCollectionDataProvider
     *
     * @param list<array<string, mixed>> $productOfferServicesStorageData
     * @param array<string, mixed> $filterData
     * @param list<int> $productOfferServiceIds
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetProductOfferServiceStorageSynchronizationDataTransfers(
        array $productOfferServicesStorageData,
        array $filterData,
        array $productOfferServiceIds,
        int $expectedCount
    ): void {
        // Arrange
        foreach ($productOfferServicesStorageData as $productOfferServiceStorageData) {
            $productOfferServiceStorageTransfer = (new ProductOfferServiceStorageTransfer())->fromArray($productOfferServiceStorageData, true);
            $this->tester->createProductOfferServiceStorageByStoreRelations($productOfferServiceStorageTransfer, [
                ProductOfferServicePointStorageBusinessTester::STORE_NAME_DE,
            ]);
        }

        $filterTransfer = (new FilterTransfer())->fromArray($filterData, true);

        // Act
        $productOfferServiceStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferServiceStorageSynchronizationDataTransfers($filterTransfer, $productOfferServiceIds);

        // Assert
        $this->assertCount($expectedCount, $productOfferServiceStorageSynchronizationDataTransfers);
    }

    /**
     * @return void
     */
    public function testShouldReturnCorrectCollectionWhenCorrectProductOfferServiceIdsAreProvided(): void
    {
        // Arrange
        $expectedProductOfferReferences = [];
        $productOfferServiceIds = [];
        for ($i = 0; $i < 2; $i++) {
            $productOfferTransfer = $this->tester->haveProductOffer();

            $productOfferServiceTransfer = $this->tester->haveProductOfferService([
                ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
                ProductOfferServiceTransfer::ID_SERVICE => $this->tester->haveService()->getIdServiceOrFail(),
            ]);

            $productOfferServiceStorageTransfer = (new ProductOfferServiceStorageTransfer())
                ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail());

            $this->tester->createProductOfferServiceStorageByStoreRelations($productOfferServiceStorageTransfer, [
                ProductOfferServicePointStorageBusinessTester::STORE_NAME_DE,
            ]);

            $expectedProductOfferReferences[] = $productOfferTransfer->getProductOfferReferenceOrFail();
            $productOfferServiceIds[] = $this->tester->findIdProductOfferService($productOfferServiceTransfer);
        }

        $filterTransfer = (new FilterTransfer())->fromArray([
            FilterTransfer::OFFSET => 0,
            FilterTransfer::LIMIT => 2,
        ]);

        // Act
        $productOfferServiceStorageSynchronizationDataTransfers = $this->tester->getFacade()
            ->getProductOfferServiceStorageSynchronizationDataTransfers($filterTransfer, $productOfferServiceIds);

        // Assert
        $this->assertCount(2, $productOfferServiceStorageSynchronizationDataTransfers);

        $resultProductOfferReferences = $this->extractProductOfferReferencesFromSynchronizationDataTransfers($productOfferServiceStorageSynchronizationDataTransfers);
        $this->assertSame($expectedProductOfferReferences, $resultProductOfferReferences);
    }

    /**
     * @return array<list<array<string, mixed>|int|list<int>>>
     */
    protected function getCollectionDataProvider(): array
    {
        return [
            'Should return empty collection when product offer service storage data is empty' => [
                [], [FilterTransfer::OFFSET => 0, FilterTransfer::LIMIT => 1], [], 0,
            ],
            'Should return empty collection when offset is higher then number of product offer service storage' => [
                [[ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => ProductOfferServicePointStorageBusinessTester::PRODUCT_OFFER_REFERENCE]],
                [FilterTransfer::OFFSET => 1, FilterTransfer::LIMIT => 1],
                [],
                0,
            ],
            'Should return empty collection when search by incorrect product offer service ids' => [
                [[ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => ProductOfferServicePointStorageBusinessTester::PRODUCT_OFFER_REFERENCE]],
                [FilterTransfer::OFFSET => 0, FilterTransfer::LIMIT => 1],
                [static::FAKE_ID_SERVICE_POINT],
                0,
            ],
            'Should return collection when correct limit is provided' => [
                [[ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => ProductOfferServicePointStorageBusinessTester::PRODUCT_OFFER_REFERENCE]],
                [FilterTransfer::OFFSET => 0, FilterTransfer::LIMIT => 1],
                [],
                1,
            ],
            'Should return collection when correct offset is provided' => [
                [
                    [ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => ProductOfferServicePointStorageBusinessTester::PRODUCT_OFFER_REFERENCE],
                    [ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => ProductOfferServicePointStorageBusinessTester::PRODUCT_OFFER_REFERENCE_2],
                ],
                [FilterTransfer::OFFSET => 1, FilterTransfer::LIMIT => 1],
                [],
                1,
            ],
        ];
    }

    /**
     * @param list<\Generated\Shared\Transfer\SynchronizationDataTransfer> $synchronizationDataTransfers
     *
     * @return list<string>
     */
    protected function extractProductOfferReferencesFromSynchronizationDataTransfers(array $synchronizationDataTransfers): array
    {
        $productOfferReferences = [];
        foreach ($synchronizationDataTransfers as $synchronizationDataTransfer) {
            $productOfferReferences[] = (string)$synchronizationDataTransfer->getData()[ProductOfferServicePointStorageBusinessTester::KEY_PRODUCT_OFFER_REFERENCE];
        }

        return $productOfferReferences;
    }
}
