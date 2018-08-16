<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipMinimumOrderValue;

use Codeception\Actor;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MinimumOrderValueThresholdTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantRelationshipMinimumOrderValueBusinessTester extends Actor
{
    use _generated\MerchantRelationshipMinimumOrderValueBusinessTesterActions;

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createTestMerchantRelationship(string $key): MerchantRelationshipTransfer
    {
        $idMerchant = $this->haveMerchant()->getIdMerchant();
        $idCompanyBusinessUnit = $this->haveCompanyBusinessUnit()->getIdCompanyBusinessUnit();

        return $this->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => $key,
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $idCompanyBusinessUnit,
        ]);
    }

    /**
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function createTestMerchantRelationshipMinimumOrderValueTransfer(
        string $strategyKey,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $minimumOrderValueTypeTransfer = $this->createTestMinimumOrderValueTypeTransfer($strategyKey);

        return (new MerchantRelationshipMinimumOrderValueTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setThreshold(
                (new MinimumOrderValueThresholdTransfer())
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
    protected function createTestMinimumOrderValueTypeTransfer(string $strategyKey): MinimumOrderValueTypeTransfer
    {
        return (new MinimumOrderValueTypeTransfer())
            ->setKey($strategyKey);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createTestQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setStore($this->createTestStoreTransfer())
            ->setCurrency($this->createTestCurrencyTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createTestStoreTransfer(): StoreTransfer
    {
        return (new StoreTransfer())
            ->setIdStore(1)
            ->setName('DE');
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function createTestCurrencyTransfer(): CurrencyTransfer
    {
        return (new CurrencyTransfer())
            ->setIdCurrency(1)
            ->setCode('EUR');
    }
}
