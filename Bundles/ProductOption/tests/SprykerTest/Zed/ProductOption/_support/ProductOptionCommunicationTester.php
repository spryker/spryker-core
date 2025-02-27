<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption;

use Codeception\Actor;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOptionQuery;
use Spryker\Zed\Money\Communication\Plugin\Form\MoneyCollectionFormTypePlugin as MoneyCollectionFormTypePluginWithoutLocale;
use Spryker\Zed\MoneyGui\Communication\Plugin\Form\MoneyCollectionFormTypePlugin;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;

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
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOptionCommunicationTester extends Actor
{
    use _generated\ProductOptionCommunicationTesterActions;

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->getContainer()->set(static::SERVICE_CURRENCY, static::DEFAULT_CURRENCY);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionGroup
     *
     * @return void
     */
    public function createProductOptionValueEntity(string $sku, int $idProductOptionGroup): void
    {
        $productOptionValue = new SpyProductOptionValue();
        $productOptionValue->setSku($sku);
        $productOptionValue->setValue($sku);
        $productOptionValue->setFkProductOptionGroup($idProductOptionGroup);

        $productOptionValue->save();
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePlugin(): void
    {
        $this->setDependency(ProductOptionDependencyProvider::MONEY_COLLECTION_FORM_TYPE_PLUGIN, function () {
            return new MoneyCollectionFormTypePlugin();
        });
    }

    /**
     * @return void
     */
    public function registerMoneyCollectionFormTypePluginWithoutLocale(): void
    {
        $this->setDependency(ProductOptionDependencyProvider::MONEY_COLLECTION_FORM_TYPE_PLUGIN, function () {
            return new MoneyCollectionFormTypePluginWithoutLocale();
        });
    }

    /**
     * @return void
     */
    public function ensureSalesOrderItemOptionDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSalesOrderItemOptionQuery(),
        );
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption
     */
    public function findSalesOrderItemOption(int $idSalesOrderItem): SpySalesOrderItemOption
    {
        return $this->getSalesOrderItemOptionQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemOptionQuery
     */
    public function getSalesOrderItemOptionQuery(): SpySalesOrderItemOptionQuery
    {
        return SpySalesOrderItemOptionQuery::create();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function createProductOption(StoreTransfer $storeTransfer): ProductOptionTransfer
    {
        $productOptionGroupTransfer = $this->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [
                            ProductOptionGroupDataHelper::STORE_NAME => $storeTransfer->getName(),
                            MoneyValueTransfer::GROSS_AMOUNT => 123,
                            MoneyValueTransfer::NET_AMOUNT => 123,
                        ],
                    ],
                ],
            ],
        );

        return (new ProductOptionTransfer())
            ->fromArray($productOptionGroupTransfer->getProductOptionValues()[0]->toArray(), true)
            ->setGroupName($productOptionGroupTransfer->getName())
            ->setQuantity(1)
            ->setUnitGrossPrice(123)
            ->setTaxRate(19.0);
    }
}
