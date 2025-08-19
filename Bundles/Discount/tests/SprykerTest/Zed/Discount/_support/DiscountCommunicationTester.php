<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\DiscountConfiguratorBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountTableCriteriaTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

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
 * @SuppressWarnings(\SprykerTest\Zed\Discount\PHPMD)
 */
class DiscountCommunicationTester extends Actor
{
    use _generated\DiscountCommunicationTesterActions;

    /**
     * @return void
     */
    public function registerStoreRelationToggleFormTypePlugin(): void
    {
        $this->setDependency(DiscountDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE, function () {
            return new StoreRelationToggleFormTypePlugin();
        });
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin(): void
    {
        $moneyCollectionTypeMock = Stub::makeEmpty(FormTypeInterface::class, [
            'getType' => '\Spryker\Zed\MoneyGui\Communication\Form\Type\MoneyCollectionType',
        ]);

        $this->setDependency(DiscountDependencyProvider::PLUGIN_MONEY_COLLECTION_FORM_TYPE, $moneyCollectionTypeMock);
    }

    /**
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function haveDiscountConfiguratorTransfer(): DiscountConfiguratorTransfer
    {
        $discountConfigurator = (new DiscountConfiguratorBuilder())
            ->withDiscountCalculator()
            ->build();

        $discountConfigurator->getDiscountCalculator()->addMoneyValue(
            (new MoneyValueBuilder())
                ->withCurrency()
                ->build(),
        );

        return $discountConfigurator;
    }

    /**
     * @return array<string>
     */
    public function getAllAvailableCurrencyCodes(): array
    {
        $codes = [];
        $storeWithCurrenciesTransfers = $this->getLocator()->currency()->facade()->getAllStoresWithCurrencies();

        foreach ($storeWithCurrenciesTransfers as $storeWithCurrenciesTransfer) {
            foreach ($storeWithCurrenciesTransfer->getCurrencies() as $currencyTransfer) {
                $codes[] = $currencyTransfer->getSymbol();
            }
        }

        return $codes;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\DiscountTableCriteriaTransfer
     */
    public function createDiscountTableCriteriaTransfer(array $seedData = []): DiscountTableCriteriaTransfer
    {
        return (new DiscountTableCriteriaTransfer())
            ->fromArray($seedData, true);
    }

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return int
     */
    public function getDiscountVoucherEntitiesCountByIdDiscountVoucherPool(int $idDiscountVoucherPool): int
    {
        return $this->getDiscountVoucherQuery()->filterByFkDiscountVoucherPool($idDiscountVoucherPool)->count();
    }

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return int
     */
    public function getDiscountVoucherPoolEntitiesCountByIdDiscountVoucherPool(int $idDiscountVoucherPool): int
    {
        return $this->getDiscountVoucherPoolQuery()->filterByIdDiscountVoucherPool($idDiscountVoucherPool)->count();
    }

    /**
     * @param int $idDiscountVoucherPool
     *
     * @return void
     */
    public function unsetFkDiscountVoucherPool(int $idDiscountVoucherPool): void
    {
        $this->getDiscountQuery()
            ->filterByFkDiscountVoucherPool($idDiscountVoucherPool)
            ->update(['FkDiscountVoucherPool' => null]);
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected function getDiscountQuery(): SpyDiscountQuery
    {
        return SpyDiscountQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    protected function getDiscountVoucherQuery(): SpyDiscountVoucherQuery
    {
        return SpyDiscountVoucherQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    protected function getDiscountVoucherPoolQuery(): SpyDiscountVoucherPoolQuery
    {
        return SpyDiscountVoucherPoolQuery::create();
    }
}
