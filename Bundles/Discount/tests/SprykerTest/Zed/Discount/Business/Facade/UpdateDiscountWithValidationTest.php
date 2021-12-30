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
use Orm\Zed\Discount\Persistence\SpyDiscountStore;
use Spryker\Zed\Discount\DiscountDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group UpdateDiscountWithValidationTest
 * Add your own group annotations below this line
 */
class UpdateDiscountWithValidationTest extends Unit
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
     * @var int
     */
    protected const FAKE_DISCOUNT_ID = 0;

    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWillUpdateExistingRecordWithNewData(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountGeneralTransfer = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());

        $discountGeneralTransfer->setDisplayName('updated functional discount facade test');
        $discountGeneralTransfer->setDescription('Updated description');
        $discountGeneralTransfer->setIsActive(false);
        $discountGeneralTransfer->setIsExclusive(false);
        $discountGeneralTransfer->setValidFrom((new DateTime('1 year'))->format(static::DATE_TIME_FORMAT));
        $discountGeneralTransfer->setValidTo((new DateTime('+2 year'))->format(static::DATE_TIME_FORMAT));
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        $discountConfiguratorTransfer->getDiscountCalculator()
            ->setCalculatorPlugin(DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED)
            ->setAmount(5)
            ->setCollectorQueryString('sku = "new-sku"');

        $discountConfiguratorTransfer->getDiscountCondition()
            ->setDecisionRuleQueryString('sku = "new-decision-sku"');

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $idDiscount = $discountConfiguratorResponseTransfer->getDiscountConfiguratorOrFail()
            ->getDiscountGeneralOrFail()
            ->getIdDiscountOrFail();

        $discountEntity = $this->tester->findDiscountEntityById($idDiscount);
        $this->assertNotNull($discountEntity);

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
    public function testUpdateDiscountShouldRemoveDiscountAmountWhenFixedDiscountTypeChangedToPercentageType(): void
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

        $discountGeneralTransfer = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());
        $idDiscount = $discountGeneralTransfer->getIdDiscount();

        $discountConfiguratorTransfer->getDiscountGeneral()->setIdDiscount($idDiscount);
        $discountConfiguratorTransfer->getDiscountCalculator()
            ->setCalculatorPlugin(static::PLUGIN_CALCULATOR_PERCENTAGE);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);
        $discountEntity = SpyDiscountQuery::create()->leftJoinWithDiscountAmount()->findByIdDiscount($idDiscount)->getFirst();

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $discountEntity->getDiscountAmounts());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountShouldNotRemoveDiscountAmountWhenFixedDiscountTypeChangedToPluginWithCalculatorMoneyInputType(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountGeneralTransfer = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $idDiscount = $discountGeneralTransfer->getIdDiscount();

        $discountConfiguratorTransfer->getDiscountGeneral()
            ->setDescription('test')
            ->setIdDiscount($idDiscount);
        $discountConfiguratorTransfer->getDiscountCalculator()
            ->addMoneyValue(
                (new MoneyValueTransfer())
                    ->setGrossAmount(50)
                    ->setFkCurrency($currencyTransfer->getIdCurrency()),
            );

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);
        $discountEntity = SpyDiscountQuery::create()->leftJoinWithDiscountAmount()->findByIdDiscount($idDiscount)->getFirst();

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $discountEntity->getDiscountAmounts());
    }

    /**
     * @return void
     */
    public function testUpdateDiscountShouldRemoveDiscountAmountWhenFixedDiscountTypeChangedToPluginThatWasntFoundInStack(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountGeneralTransfer = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());
        $currencyTransfer = $this->tester->haveCurrencyTransfer();
        $idDiscount = $discountGeneralTransfer->getIdDiscount();

        $discountConfiguratorTransfer->getDiscountGeneral()
            ->setDescription('test')
            ->setIdDiscount($idDiscount);
        $discountConfiguratorTransfer->getDiscountCalculator()
            ->setCalculatorPlugin('not_registered_plugin')
            ->addMoneyValue(
                (new MoneyValueTransfer())
                    ->setGrossAmount(50)
                    ->setFkCurrency($currencyTransfer->getIdCurrency()),
            );

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);
        $discountEntity = SpyDiscountQuery::create()->leftJoinWithDiscountAmount()->findByIdDiscount($idDiscount)->getFirst();

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $discountEntity->getDiscountAmounts());
    }

    /**
     * @return void
     */
    public function testWillPersistsStoreRelation(): void
    {
        // Arrange
        $storeAtTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'AT']);
        $storeDeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $storeUsTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'US']);

        $originalIdStores = [$storeDeTransfer->getIdStore()];
        $expectedIdStores = [$storeAtTransfer->getIdStore(), $storeUsTransfer->getIdStore()];

        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer($originalIdStores);
        $discountGeneralTransfer = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        $discountConfiguratorTransfer
            ->getDiscountGeneral()
            ->getStoreRelation()
            ->setIdStores($expectedIdStores);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);

        // Assert
        $this->assertTrue($discountConfiguratorResponseTransfer->getIsSuccessful());
        $idDiscount = $discountConfiguratorResponseTransfer->getDiscountConfiguratorOrFail()
            ->getDiscountGeneralOrFail()
            ->getIdDiscountOrFail();

        // Assert
        $discountStoreEntityCollection = $this->tester->getDiscountStoreEntityCollectionByIdDiscount($idDiscount);
        $this->assertCount(2, $discountStoreEntityCollection);

        $persistedStoreIds = $this->extractStoreIdsFromDiscountStoreEntityCollection($discountStoreEntityCollection);
        $this->assertSame($expectedIdStores, $persistedStoreIds);
    }

    /**
     * @return void
     */
    public function testWillValidateDatesFormat(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountGeneralTransfer = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());

        $discountGeneralTransfer
            ->setValidFrom('NOT_A_DATE')
            ->setValidTo('1234567890');
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneralTransfer);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);

        // Assert
        $this->assertFalse($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(3, $discountConfiguratorResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testWillValidateDatePeriod(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountGeneral = $this->tester->haveDiscount($discountConfiguratorTransfer->toArray());

        $discountGeneral
            ->setValidFrom((new DateTime('+1 day'))->format(static::DATE_TIME_FORMAT))
            ->setValidTo((new DateTime('-1 day'))->format(static::DATE_TIME_FORMAT));
        $discountConfiguratorTransfer->setDiscountGeneral($discountGeneral);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);

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
    public function testWillValidateIfDiscountExists(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->tester->createDiscountConfiguratorTransfer();
        $discountConfiguratorTransfer->getDiscountGeneral()->setIdDiscount(static::FAKE_DISCOUNT_ID);

        // Act
        $discountConfiguratorResponseTransfer = $this->tester->getFacade()->updateDiscountWithValidation($discountConfiguratorTransfer);

        // Assert
        $this->assertFalse($discountConfiguratorResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $discountConfiguratorResponseTransfer->getMessages());

        /** @var \Generated\Shared\Transfer\MessageTransfer $messageTransfer */
        $messageTransfer = $discountConfiguratorResponseTransfer->getMessages()->offsetGet(0);
        $this->assertSame('Discount with id %s doesn\'t exist', $messageTransfer->getValue());
        $this->assertArrayHasKey('%s', $messageTransfer->getParameters());
        $this->assertSame(static::FAKE_DISCOUNT_ID, $messageTransfer->getParameters()['%s']);
    }

    /**
     * @param array<\Orm\Zed\Discount\Persistence\SpyDiscountStore> $discountStoreEntityCollection
     *
     * @return array<int>
     */
    protected function extractStoreIdsFromDiscountStoreEntityCollection(array $discountStoreEntityCollection): array
    {
        return array_map(function (SpyDiscountStore $discountStoreEntity) {
            return $discountStoreEntity->getFkStore();
        }, $discountStoreEntityCollection);
    }
}
