<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Facade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Shared\Discount\DiscountConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group CreateDiscountTest
 * Add your own group annotations below this line
 */
class CreateDiscountTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Discount\Persistence\Propel\Mapper\DiscountMapper::DATE_TIME_FORMAT
     *
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @uses \Spryker\Zed\Discount\DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE
     *
     * @var string
     */
    protected const PLUGIN_CALCULATOR_PERCENTAGE = 'PLUGIN_CALCULATOR_PERCENTAGE';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWillPersistAllConfiguredData(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()
            ->createDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $idDiscount = $discountConfiguratorResponseTransfer->getDiscountConfiguratorOrFail()
            ->getDiscountGeneralOrFail()
            ->getIdDiscountOrFail();

        $discountEntity = $this->tester->findDiscountEntityById($idDiscount);
        $this->assertNotNull($discountEntity);

        $discountGeneralTransfer = $discountConfiguratorTransfer->getDiscountGeneral();
        $this->assertSame($discountGeneralTransfer->getDisplayName(), $discountEntity->getDisplayName());
        $this->assertSame($discountGeneralTransfer->getIsActive(), $discountEntity->getIsActive());
        $this->assertSame($discountGeneralTransfer->getIsExclusive(), $discountEntity->getIsExclusive());
        $this->assertSame($discountGeneralTransfer->getDescription(), $discountEntity->getDescription());
        $this->assertSame($discountGeneralTransfer->getValidFrom(), $discountEntity->getValidFrom()->format(static::DATE_TIME_FORMAT));
        $this->assertSame($discountGeneralTransfer->getValidTo(), $discountEntity->getValidTo()->format(static::DATE_TIME_FORMAT));

        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();
        $this->assertSame($discountCalculatorTransfer->getAmount(), $discountEntity->getAmount());
        $this->assertSame($discountCalculatorTransfer->getCalculatorPlugin(), $discountEntity->getCalculatorPlugin());
        $this->assertSame($discountCalculatorTransfer->getCollectorQueryString(), $discountEntity->getCollectorQueryString());

        $discountConditionTransfer = $discountConfiguratorTransfer->getDiscountCondition();
        $this->assertSame($discountConditionTransfer->getDecisionRuleQueryString(), $discountEntity->getDecisionRuleQueryString());
    }

    /**
     * @return void
     */
    public function testWillCreateDiscountWithEmptyVoucherPool(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()
            ->setDiscountType(DiscountConstants::TYPE_VOUCHER);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()
            ->createDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $idDiscount = $discountConfiguratorResponseTransfer->getDiscountConfiguratorOrFail()
            ->getDiscountGeneralOrFail()
            ->getIdDiscountOrFail();

        $discountEntity = $this->tester->findDiscountEntityById($idDiscount);
        $this->assertNotNull($discountEntity);

        $this->assertNotEmpty($discountEntity->getFkDiscountVoucherPool());
        $discountVoucherPoolEntity = $discountEntity->getVoucherPool();
        $this->assertNotEmpty($discountVoucherPoolEntity);
        $this->assertSame($discountConfiguratorTransfer->getDiscountGeneral()->getDisplayName(), $discountVoucherPoolEntity->getName());
    }

    /**
     * @return void
     */
    public function testWillPersisStoreRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);
        $storeIds = [$storeTransfer->getIdStore()];

        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer($storeIds);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->createDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());

        $idDiscount = $discountConfiguratorResponseTransfer->getDiscountConfiguratorOrFail()
            ->getDiscountGeneralOrFail()
            ->getIdDiscountOrFail();

        $discountStoreEntityCollection = $this->tester->getDiscountStoreEntityCollectionByIdDiscount($idDiscount);
        $this->assertCount(1, $discountStoreEntityCollection);
        $this->assertSame($storeTransfer->getIdStore(), $discountStoreEntityCollection[0]->getFkStore());
    }

    /**
     * @return void
     */
    public function testWillValidateDatesFormat(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()
            ->setValidFrom('NOT_A_DATE')
            ->setValidTo('1234567890');

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->createDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertFalse($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(4, $discountConfiguratorResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testWillValidateDatesMaxValue(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()
            ->setValidFrom((new DateTime('+1 day'))->format(static::DATE_TIME_FORMAT))
            ->setValidTo((new DateTime('2038-01-19 03:14:08'))->format(static::DATE_TIME_FORMAT));

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->createDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertFalse($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $discountConfiguratorResponseTransfer->getMessages());

        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        $messageTransfer = $discountConfiguratorResponseTransfer->getMessages()->offsetGet(0);
        $this->assertSame('Date cannot be later than {{ compared_value }}', $messageTransfer->getValue());
        $this->assertArrayHasKey('{{ compared_value }}', $messageTransfer->getParameters());
    }

    /**
     * @return void
     */
    public function testWillValidateDatePeriod(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()
            ->setValidFrom((new DateTime('+1 day'))->format(static::DATE_TIME_FORMAT))
            ->setValidTo((new DateTime('-1 day'))->format(static::DATE_TIME_FORMAT));

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->createDiscount($discountConfiguratorTransfer);

        // Assert
        $this->assertFalse($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $discountConfiguratorResponseTransfer->getMessages());

        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        $messageTransfer = $discountConfiguratorResponseTransfer->getMessages()->offsetGet(0);
        $this->assertSame('The discount cannot end before the starting date.', $messageTransfer->getValue());
    }

    /**
     * @return void
     */
    public function testCreateDiscountShouldNotAddDiscountAmountForPercentageType(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountCalculator()
            ->setAmount(10)
            ->setCalculatorPlugin(static::PLUGIN_CALCULATOR_PERCENTAGE);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->createDiscount($discountConfiguratorTransfer);
        $discountEntity = SpyDiscountQuery::create()
            ->leftJoinWithDiscountAmount()
            ->findByIdDiscount($discountConfiguratorResponseTransfer->getDiscountConfigurator()->getDiscountGeneral()->getIdDiscount())
            ->getFirst();

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertEmpty($discountEntity->getDiscountAmounts());
    }

    /**
     * @return void
     */
    public function testCreateDiscountShouldAddDiscountAmountForFixedType(): void
    {
        // Arrange
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();

        $discountConfiguratorTransfer->getDiscountCalculator()
            ->addMoneyValue(
                (new MoneyValueTransfer())
                    ->setGrossAmount(50)
                    ->setFkCurrency($currencyTransfer->getIdCurrency()),
            );

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->createDiscount($discountConfiguratorTransfer);
        $discountEntity = SpyDiscountQuery::create()
            ->leftJoinWithDiscountAmount()
            ->findByIdDiscount($discountConfiguratorResponseTransfer->getDiscountConfigurator()->getDiscountGeneral()->getIdDiscount())
            ->getFirst();

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $discountEntity->getDiscountAmounts());
    }
}
