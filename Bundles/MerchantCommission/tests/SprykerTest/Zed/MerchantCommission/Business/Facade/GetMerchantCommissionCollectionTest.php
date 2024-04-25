<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionAmountBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionConditionsTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Business
 * @group Facade
 * @group GetMerchantCommissionCollectionTest
 * Add your own group annotations below this line
 */
class GetMerchantCommissionCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @var string
     */
    protected const CURRENCY_CODE_USD = 'USD';

    /**
     * @var \SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester
     */
    protected MerchantCommissionBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantCommissionDatabaseIsEmpty();
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollection(): void
    {
        // Arrange
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            new MerchantCommissionCriteriaTransfer(),
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionExpandedWithStoreRelations(): void
    {
        // Arrange
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addStores($storeDeTransfer)
                ->addStores($storeAtTransfer),
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())
            ->setWithStoreRelations(true);
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionTransfer->getIdMerchantCommission(),
        );

        $storeRelationTransfer = $merchantCommissionTransfer->getStoreRelation();
        $this->assertNotNull($storeRelationTransfer);
        $this->assertCount(2, $storeRelationTransfer->getStores());
        $this->assertTrue($this->tester->storeRelationTransferHasStore($storeRelationTransfer, $storeDeTransfer));
        $this->assertTrue($this->tester->storeRelationTransferHasStore($storeRelationTransfer, $storeAtTransfer));
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionExpandedWithMerchantRelations(): void
    {
        // Arrange
        $merchant1Transfer = $this->tester->haveMerchant();
        $merchant2Transfer = $this->tester->haveMerchant();
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANTS => [
                $merchant1Transfer->toArray(),
                $merchant2Transfer->toArray(),
            ],
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())
            ->setWithMerchantRelations(true);
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $merchantCommissionTransfer = $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionTransfer->getIdMerchantCommission(),
        );

        $merchantTransfers = $merchantCommissionTransfer->getMerchants();
        $this->assertCount(2, $merchantTransfers);
        $this->assertTrue($this->tester->merchantCollectionHasMerchant($merchantTransfers, $merchant1Transfer));
        $this->assertTrue($this->tester->merchantCollectionHasMerchant($merchantTransfers, $merchant2Transfer));
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionExpandedWithMerchantCommissionAmounts(): void
    {
        // Arrange
        $currencyEurTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $currencyUsdTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD]);
        $merchantCommissionAmount1Transfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => $currencyEurTransfer,
        ]))->build();
        $merchantCommissionAmount2Transfer = (new MerchantCommissionAmountBuilder([
            MerchantCommissionAmountTransfer::CURRENCY => $currencyUsdTransfer,
        ]))->build();

        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_AMOUNTS => [
                $merchantCommissionAmount1Transfer->toArray(),
                $merchantCommissionAmount2Transfer->toArray(),
            ],
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())
            ->setWithCommissionMerchantAmountRelations(true);
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $resultMerchantCommissionTransfer = $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current();
        $this->assertSame(
            $resultMerchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $resultMerchantCommissionTransfer->getIdMerchantCommission(),
        );

        $merchantCommissionAmountTransfers = $resultMerchantCommissionTransfer->getMerchantCommissionAmounts();
        $this->assertCount(2, $merchantCommissionAmountTransfers);
        $this->assertTrue($this->tester->merchantCommissionAmountCollectionHasMerchantCommissionAmount(
            $merchantCommissionAmountTransfers,
            $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(0),
        ));
        $this->assertTrue($this->tester->merchantCommissionAmountCollectionHasMerchantCommissionAmount(
            $merchantCommissionAmountTransfers,
            $merchantCommissionTransfer->getMerchantCommissionAmounts()->offsetGet(1),
        ));
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByIdMerchantCommission(): void
    {
        // Arrange
        $this->tester->createMerchantCommission();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->addIdMerchantCommission(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
        );
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByUuid(): void
    {
        // Arrange
        $this->tester->createMerchantCommission();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->addUuid(
            $merchantCommissionTransfer->getUuidOrFail(),
        );
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByKey(): void
    {
        // Arrange
        $this->tester->createMerchantCommission();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission();

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->addKey(
            $merchantCommissionTransfer->getKeyOrFail(),
        );
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByIsActiveStatus(): void
    {
        // Arrange
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::IS_ACTIVE => true,
        ]);
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::IS_ACTIVE => false,
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->setIsActive(false);
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByStoreNames(): void
    {
        // Arrange
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);

        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeDeTransfer),
        ]);
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeAtTransfer),
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->addStoreName(
            $storeAtTransfer->getNameOrFail(),
        );
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByMerchantIds(): void
    {
        // Arrange
        $merchant1Transfer = $this->tester->haveMerchant();
        $merchant2Transfer = $this->tester->haveMerchant();

        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANTS => [$merchant1Transfer->toArray()],
        ]);
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANTS => [$merchant2Transfer->toArray()],
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->addIdMerchant(
            $merchant2Transfer->getIdMerchantOrFail(),
        );
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByMerchantCommissionGroupName(): void
    {
        // Arrange
        $merchantCommissionGroup1Transfer = $this->tester->haveMerchantCommissionGroup();
        $merchantCommissionGroup2Transfer = $this->tester->haveMerchantCommissionGroup();

        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup1Transfer,
        ]);
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup2Transfer,
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->addMerchantCommissionGroupName(
            $merchantCommissionGroup2Transfer->getNameOrFail(),
        );
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsMerchantCommissionCollectionFilteredByStoreNamesWithoutDuplicates(): void
    {
        // Arrange
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addStores($storeDeTransfer)
                ->addStores($storeAtTransfer),
        ]);

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())
            ->addStoreName($storeDeTransfer->getNameOrFail())
            ->addStoreName($storeAtTransfer->getNameOrFail());
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );

        // Act
        $merchantCommissionCollectionTransfer = $this->tester->getFacade()->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $merchantCommissionCollectionTransfer->getMerchantCommissions());
        $this->assertSame(
            $merchantCommissionTransfer->getIdMerchantCommissionOrFail(),
            $merchantCommissionCollectionTransfer->getMerchantCommissions()->getIterator()->current()->getIdMerchantCommission(),
        );
    }
}
