<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThreshold;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\GlossaryKeyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Generated\Shared\Transfer\SpyMerchantRelationshipEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;

/**
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
class MerchantRelationshipSalesOrderThresholdBusinessTester extends Actor
{
    use _generated\MerchantRelationshipSalesOrderThresholdBusinessTesterActions;

    /**
     * @var string
     */
    protected const TEST_GLOSSARY_KEY = 'test_glossary_key';

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createTestMerchantRelationship(string $key): MerchantRelationshipTransfer
    {
        $idMerchant = $this->haveMerchant()->getIdMerchant();
        $idCompanyBusinessUnit = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
        ])->getIdCompanyBusinessUnit();

        return $this->haveMerchantRelationship([
            SpyMerchantRelationshipEntityTransfer::MERCHANT_RELATIONSHIP_KEY => $key,
            SpyMerchantRelationshipEntityTransfer::FK_MERCHANT => $idMerchant,
            SpyMerchantRelationshipEntityTransfer::FK_COMPANY_BUSINESS_UNIT => $idCompanyBusinessUnit,
        ]);
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function createTestMerchantRelationshipSalesOrderThresholdTransfer(
        string $salesOrderThresholdTypeKey,
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MerchantRelationshipSalesOrderThresholdTransfer {
        $salesOrderThresholdTypeTransfer = $this->createTestSalesOrderThresholdTypeTransfer($salesOrderThresholdTypeKey);

        return (new MerchantRelationshipSalesOrderThresholdTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer)
            ->setStore($storeTransfer)
            ->setCurrency($currencyTransfer)
            ->setSalesOrderThresholdValue(
                (new SalesOrderThresholdValueTransfer())
                    ->setSalesOrderThresholdType($salesOrderThresholdTypeTransfer)
                    ->setThreshold($thresholdValue)
                    ->setMessageGlossaryKey(static::TEST_GLOSSARY_KEY)
                    ->setFee($fee),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer
     */
    public function createTestMerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer {
        return (new MerchantRelationshipSalesOrderThresholdCollectionDeleteCriteriaTransfer())
            ->setIsTransactional(true)
            ->addMerchantRelationshipId($merchantRelationshipTransfer->getIdMerchantRelationship());
    }

    /**
     * @param string $salesOrderThresholdTypeKey
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    protected function createTestSalesOrderThresholdTypeTransfer(string $salesOrderThresholdTypeKey): SalesOrderThresholdTypeTransfer
    {
        return (new SalesOrderThresholdTypeTransfer())
            ->setKey($salesOrderThresholdTypeKey);
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

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return int
     */
    public function countMerchantRelationshipSalesOrderThresholds(MerchantRelationshipTransfer $merchantRelationshipTransfer): int
    {
        return $this->getMerchantRelationshipSalesOrderThresholdQuery()
            ->findByFkMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship())
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer|null
     */
    public function findGlossaryKey(MerchantRelationshipSalesOrderThresholdTransfer $merchantRelationshipSalesOrderThresholdTransfer): ?GlossaryKeyTransfer
    {
        $glossaryKeyEntity = $this->getGlossaryKeyQuery()->findOneByKey(
            $merchantRelationshipSalesOrderThresholdTransfer->getSalesOrderThresholdValue()->getMessageGlossaryKey(),
        );

        if ($glossaryKeyEntity === null) {
            return null;
        }

        return (new GlossaryKeyTransfer())->fromArray($glossaryKeyEntity->toArray(), true);
    }

    /**
     * @return \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    protected function getMerchantRelationshipSalesOrderThresholdQuery(): SpyMerchantRelationshipSalesOrderThresholdQuery
    {
        return SpyMerchantRelationshipSalesOrderThresholdQuery::create();
    }

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    protected function getGlossaryKeyQuery(): SpyGlossaryKeyQuery
    {
        return SpyGlossaryKeyQuery::create();
    }
}
