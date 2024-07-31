<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantCommissionCalculationRequestBuilder;
use Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface;
use SprykerTest\Zed\MerchantCommission\MerchantCommissionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Business
 * @group Facade
 * @group CalculateMerchantCommissionTest
 * Add your own group annotations below this line
 */
class CalculateMerchantCommissionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider::FACADE_RULE_ENGINE
     *
     * @var string
     */
    protected const FACADE_RULE_ENGINE = 'FACADE_RULE_ENGINE';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';

    /**
     * @var int
     */
    protected const TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT = 100;

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
        $this->tester->addTestCalculatorPluginToDependencies(static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT);
    }

    /**
     * @return void
     */
    public function testCalculatesMerchantCommissionForItemsAndOrderWhenMerchantCommissionDoesNotHaveOrderAndItemConditions(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
                MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
                MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
                MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->withAnotherItem([
                MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
                MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 2,
                MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->withStore($storeTransfer->toArray())
            ->build();

        $this->tester->setDependency(
            static::FACADE_RULE_ENGINE,
            $this->getRuleEngineFacadeMock($merchantCommissionCalculationRequestTransfer->getItems()->getArrayCopy()),
        );

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(2, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(0),
            [$merchantCommissionTransfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
        );
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(1),
            [$merchantCommissionTransfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
        );

        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT * 2,
            $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal(),
        );
    }

    /**
     * @return void
     */
    public function testCalculatesMerchantCommissionForItemsAndOrderWhenMerchantCommissionOrderAndItemConditionsAreMet(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::ORDER_CONDITION => 'price-mode = "GROSS_PRICE"',
            MerchantCommissionTransfer::ITEM_CONDITION => 'item-sku = "test-sku"',
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withAnotherItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 2,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())
            ->build();

        $this->tester->setDependency(
            static::FACADE_RULE_ENGINE,
            $this->getRuleEngineFacadeMock($merchantCommissionCalculationRequestTransfer->getItems()->getArrayCopy()),
        );

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(2, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(0),
            [$merchantCommissionTransfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
        );
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(1),
            [$merchantCommissionTransfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
        );

        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT * 2,
            $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal(),
        );
    }

    /**
     * @return void
     */
    public function testCalculatesMerchantCommissionOnlyForMerchantInMerchantAllowList(): void
    {
        $storeTransfer = $this->tester->haveStore();
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [$merchantTransfer->toArray()],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
        ])->withAnotherItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 2,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())
            ->build();

        $this->tester->setDependency(static::FACADE_RULE_ENGINE, $this->getRuleEngineFacadeMock([]));

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertSame(1, $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(0)->getIdSalesOrderItem());
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(0),
            [$merchantCommissionTransfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
        );

        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
            $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal(),
        );
    }

    /**
     * @return void
     */
    public function testAppliesOnlyOneMerchantCommissionPerMerchantCommissionGroupBasedOnPriority(): void
    {
        $storeTransfer = $this->tester->haveStore();
        $merchantCommissionGroup = $this->tester->haveMerchantCommissionGroup();
        $merchantCommissionTransfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup->toArray(),
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::PRIORITY => 1,
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $merchantCommissionGroup->toArray(),
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::PRIORITY => 2,
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);

        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())
            ->build();

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(0),
            [$merchantCommissionTransfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
        );

        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT,
            $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal(),
        );
    }

    /**
     * @return void
     */
    public function testAppliesMerchantCommissionForEachMerchantCommissionGroup(): void
    {
        $storeTransfer = $this->tester->haveStore();
        $merchantCommission1Transfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup()->toArray(),
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommission2Transfer = $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::MERCHANT_COMMISSION_GROUP => $this->tester->haveMerchantCommissionGroup()->toArray(),
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);

        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())
            ->build();

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertMerchantCommissionCalculationItem(
            $merchantCommissionCalculationResponseTransfer->getItems()->offsetGet(0),
            [$merchantCommission1Transfer, $merchantCommission2Transfer],
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT * 2,
        );

        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(
            static::TEST_MERCHANT_COMMISSION_CALCULATED_AMOUNT * 2,
            $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNotCalculateMerchantCommissionWhenMerchantReferenceIsInExcludedConfigList(): void
    {
        $this->tester->mockConfigMethod('getExcludedMerchantsFromCommission', [static::TEST_MERCHANT_REFERENCE]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->build();

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(0, $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal());
    }

    /**
     * @return void
     */
    public function testDoesNotCalculateMerchantCommissionWhenMerchantCommissionOrderConditionIsNotSatisfied(): void
    {
        $storeTransfer = $this->tester->haveStore();
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::ORDER_CONDITION => 'price-mode = "GROSS_PRICE"',
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())->build();

        $this->tester->setDependency(
            static::FACADE_RULE_ENGINE,
            $this->getRuleEngineFacadeMock($merchantCommissionCalculationRequestTransfer->getItems()->getArrayCopy(), false),
        );

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(0, $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal());
    }

    /**
     * @return void
     */
    public function testDoesNotCalculateMerchantCommissionWhenNoItemFulfillMerchantCommissionItemCondition(): void
    {
        $storeTransfer = $this->tester->haveStore();
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::ITEM_CONDITION => 'item-sku = "test-sku"',
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())->build();

        $this->tester->setDependency(static::FACADE_RULE_ENGINE, $this->getRuleEngineFacadeMock([]));

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(0, $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal());
    }

    /**
     * @return void
     */
    public function testDoesNotApplyMerchantCommissionWhenMerchantCommissionAmountIsLessOrEqualToZero(): void
    {
        $this->tester->addTestCalculatorPluginToDependencies(0);

        $storeTransfer = $this->tester->haveStore();
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::ORDER_CONDITION => 'price-mode = "GROSS_PRICE"',
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ])->withStore($storeTransfer->toArray())->build();

        $this->tester->setDependency(
            static::FACADE_RULE_ENGINE,
            $this->getRuleEngineFacadeMock($merchantCommissionCalculationRequestTransfer->getItems()->getArrayCopy(), false),
        );

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantCommissionCalculationResponseTransfer->getItems());
        $this->assertNotNull($merchantCommissionCalculationResponseTransfer->getTotals());
        $this->assertSame(0, $merchantCommissionCalculationResponseTransfer->getTotalsOrFail()->getMerchantCommissionTotal());
    }

    /**
     * @return void
     */
    public function testCalculatesMerchantCommissionForItemsAndOrderWhenMerchantCommissionRequestDoNotContainMerchantReference(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $this->tester->createMerchantCommission([
            MerchantCommissionTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            MerchantCommissionTransfer::MERCHANTS => [],
        ]);
        $merchantCommissionCalculationRequestTransfer = (new MerchantCommissionCalculationRequestBuilder([
            MerchantCommissionCalculationRequestTransfer::ID_SALES_ORDER => 1,
        ]))->withItem([
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
            MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 1,
            MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => null,
        ])
            ->withAnotherItem([
                MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER => 1,
                MerchantCommissionCalculationRequestItemTransfer::ID_SALES_ORDER_ITEM => 2,
                MerchantCommissionCalculationRequestItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
            ])
            ->withStore($storeTransfer->toArray())
            ->build();

        $this->tester->setDependency(
            static::FACADE_RULE_ENGINE,
            $this->getRuleEngineFacadeMock($merchantCommissionCalculationRequestTransfer->getItems()->getArrayCopy()),
        );

        // Act
        $merchantCommissionCalculationResponseTransfer = $this->tester->getFacade()
            ->calculateMerchantCommission($merchantCommissionCalculationRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantCommissionCalculationResponseTransfer->getItems());
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $merchantCommissionCalculationRequestItemTransfers
     * @param bool $isSatisfiedBy
     *
     * @return \Spryker\Zed\MerchantCommission\Dependency\Facade\MerchantCommissionToRuleEngineFacadeInterface
     */
    protected function getRuleEngineFacadeMock(
        array $merchantCommissionCalculationRequestItemTransfers,
        bool $isSatisfiedBy = true
    ): MerchantCommissionToRuleEngineFacadeInterface {
        $ruleEngineFacadeMock = $this->getMockBuilder(MerchantCommissionToRuleEngineFacadeInterface::class)
            ->getMock();
        $ruleEngineFacadeMock->method('isSatisfiedBy')->willReturn($isSatisfiedBy);
        $ruleEngineFacadeMock->method('collect')->willReturn($merchantCommissionCalculationRequestItemTransfers);

        return $ruleEngineFacadeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer
     * @param list<\Generated\Shared\Transfer\MerchantCommissionTransfer> $expectedMerchantCommissionTransfers
     * @param int $expectedMerchantCommissionAmount
     *
     * @return void
     */
    protected function assertMerchantCommissionCalculationItem(
        MerchantCommissionCalculationItemTransfer $merchantCommissionCalculationItemTransfer,
        array $expectedMerchantCommissionTransfers,
        int $expectedMerchantCommissionAmount
    ): void {
        $this->assertSame($expectedMerchantCommissionAmount, $merchantCommissionCalculationItemTransfer->getMerchantCommissionAmountAggregation());
        $this->assertSame($expectedMerchantCommissionAmount, $merchantCommissionCalculationItemTransfer->getMerchantCommissionAmountFullAggregation());
        $this->assertCount(count($expectedMerchantCommissionTransfers), $merchantCommissionCalculationItemTransfer->getMerchantCommissions());
        foreach ($expectedMerchantCommissionTransfers as $expectedMerchantCommission) {
            $this->assertTrue($this->isArrayContainsMerchantCommission(
                $merchantCommissionCalculationItemTransfer->getMerchantCommissions(),
                $expectedMerchantCommission->getUuid(),
            ));
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     * @param string $expectedMerchantCommissionUuid
     *
     * @return bool
     */
    protected function isArrayContainsMerchantCommission(
        ArrayObject $merchantCommissionTransfers,
        string $expectedMerchantCommissionUuid
    ): bool {
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            if ($merchantCommissionTransfer->getUuid() === $expectedMerchantCommissionUuid) {
                return true;
            }
        }

        return false;
    }
}
