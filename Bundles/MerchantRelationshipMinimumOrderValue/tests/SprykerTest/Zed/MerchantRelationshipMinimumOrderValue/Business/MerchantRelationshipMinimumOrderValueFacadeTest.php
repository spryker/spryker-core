<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipMinimumOrderValue
 * @group Business
 * @group Facade
 * @group MerchantRelationshipMinimumOrderValueFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipMinimumOrderValueFacadeTest extends MerchantRelationshipMinimumOrderValueMocks
{
    protected const HARD_STRATEGY_KEY = 'hard-threshold';
    protected const SOFT_STRATEGY_KEY = 'soft-threshold';
    protected const MERCHANT_RELATIONSHIP_KEY = 'mr-test-001';

    /**
     * @var \SprykerTest\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveMerchantRelationshipHardAndSoftThresholds(): void
    {
        $merchantRelationshipTransfer = $this->tester->createTestMerchantRelationship(static::MERCHANT_RELATIONSHIP_KEY);

        $storeTransferDE = $this->tester->createTestStoreTransfer();
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = $this->tester->createTestCurrencyTransfer();
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Action
        $hardThreshold1 = $this->getFacade()->saveMerchantRelationshipMinimumOrderValue(
            $this->tester->createTestMerchantRelationshipMinimumOrderValueTransfer(
                static::HARD_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                10000
            )
        );

        $hardThreshold2 = $this->getFacade()->saveMerchantRelationshipMinimumOrderValue(
            $this->tester->createTestMerchantRelationshipMinimumOrderValueTransfer(
                static::HARD_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold1 = $this->getFacade()->saveMerchantRelationshipMinimumOrderValue(
            $this->tester->createTestMerchantRelationshipMinimumOrderValueTransfer(
                static::SOFT_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold2 = $this->getFacade()->saveMerchantRelationshipMinimumOrderValue(
            $this->tester->createTestMerchantRelationshipMinimumOrderValueTransfer(
                static::SOFT_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferUS,
                $currencyTransferEUR,
                20000
            )
        );

        $softThreshold3 = $this->getFacade()->saveMerchantRelationshipMinimumOrderValue(
            $this->tester->createTestMerchantRelationshipMinimumOrderValueTransfer(
                static::SOFT_STRATEGY_KEY,
                $merchantRelationshipTransfer,
                $storeTransferUS,
                $currencyTransferUSD,
                20000
            )
        );

        // Assert
        $this->assertEquals($hardThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $hardThreshold2->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($hardThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold1->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold2->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold3->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($softThreshold2->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold3->getIdMerchantRelationshipMinimumOrderValue());

        // Cleanup
        $this->tester->cleanupMerchantRelationshipMinimumOrderValues();
    }

    /**
     * @return void
     */
    public function testFindApplicableThresholds(): void
    {
        // Prepare
        $quoteTransfer = $this->tester->createTestQuoteTransfer();

        // Action
        $this->getFacade()->findApplicableThresholds($quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
