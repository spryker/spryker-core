<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
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
    public function testSetMerchantRelationshipHardAndSoftThresholds(): void
    {
        $merchantRelationshipTransfer = $this->createMerchantRelationship(static::MERCHANT_RELATIONSHIP_KEY);

        $storeTransferDE = (new StoreTransfer())->setIdStore(1)->setName('DE');
        $storeTransferUS = (new StoreTransfer())->setIdStore(2)->setName('US');
        $currencyTransferEUR = (new CurrencyTransfer())->setIdCurrency(1)->setCode('EUR');
        $currencyTransferUSD = (new CurrencyTransfer())->setIdCurrency(2)->setCode('USD');

        // Action
        $hardThreshold1 = $this->getFacade()->setMerchantRelationshipThreshold(
            $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $this->createMinimumOrderValueTypeTransfer(static::HARD_STRATEGY_KEY),
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                100
            )
        );

        $hardThreshold2 = $this->getFacade()->setMerchantRelationshipThreshold(
            $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $this->createMinimumOrderValueTypeTransfer(static::HARD_STRATEGY_KEY),
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold1 = $this->getFacade()->setMerchantRelationshipThreshold(
            $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $this->createMinimumOrderValueTypeTransfer(static::SOFT_STRATEGY_KEY),
                $merchantRelationshipTransfer,
                $storeTransferDE,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold2 = $this->getFacade()->setMerchantRelationshipThreshold(
            $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $this->createMinimumOrderValueTypeTransfer(static::SOFT_STRATEGY_KEY),
                $merchantRelationshipTransfer,
                $storeTransferUS,
                $currencyTransferEUR,
                200
            )
        );

        $softThreshold3 = $this->getFacade()->setMerchantRelationshipThreshold(
            $this->createMerchantRelationshipMinimumOrderValueTransfer(
                $this->createMinimumOrderValueTypeTransfer(static::SOFT_STRATEGY_KEY),
                $merchantRelationshipTransfer,
                $storeTransferUS,
                $currencyTransferUSD,
                200
            )
        );

        $this->assertEquals($hardThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $hardThreshold2->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($hardThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold1->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold2->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($softThreshold1->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold3->getIdMerchantRelationshipMinimumOrderValue());
        $this->assertNotEquals($softThreshold2->getIdMerchantRelationshipMinimumOrderValue(), $softThreshold3->getIdMerchantRelationshipMinimumOrderValue());

        $this->tester->cleanupMerchantRelationshipMinimumOrderValues();
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function createMerchantRelationshipMinimumOrderValueTransfer(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MerchantRelationshipMinimumOrderValueTransfer {
        return (new MerchantRelationshipMinimumOrderValueTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setMinimumOrderValue(
                (new MinimumOrderValueTransfer())
                    ->setMinimumOrderValueType($minimumOrderValueTypeTransfer)
                    ->setValue($thresholdValue)
                    ->setFee($fee)
            );
    }

    /**
     * @param string $strategyKey
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    protected function createMinimumOrderValueTypeTransfer(string $strategyKey): MinimumOrderValueTypeTransfer
    {
        return (new MinimumOrderValueTypeTransfer())
            ->setKey($strategyKey);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function createMerchantRelationship(string $key): MerchantRelationshipTransfer
    {
        $idMerchant = $this->tester->haveMerchant()->getIdMerchant();
        $idCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit()->getIdCompanyBusinessUnit();

        return $this->tester->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => $key,
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $idCompanyBusinessUnit,
        ]);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipMinimumOrderValueFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
