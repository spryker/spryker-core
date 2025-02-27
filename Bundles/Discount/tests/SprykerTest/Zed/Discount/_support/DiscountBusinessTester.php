<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount;

use Codeception\Actor;
use Generated\Shared\DataBuilder\DiscountConfiguratorBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Orm\Zed\Sales\Persistence\SpySalesDiscount;
use Orm\Zed\Sales\Persistence\SpySalesDiscountCodeQuery;
use Orm\Zed\Sales\Persistence\SpySalesDiscountQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountRepository;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

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
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\Discount\PHPMD)
 */
class DiscountBusinessTester extends Actor
{
    use _generated\DiscountBusinessTesterActions;

    /**
     * @var string
     */
    public const VOUCHER_CODE = 'testCode1';

    /**
     * @var int
     */
    protected const DEFAULT_ITEM_QUANTITY = 3;

    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_EUR = 'EUR';

    /**
     * @var int
     */
    protected const DISCOUNT_VOUCHER_POOL_NAME_LENGTH = 10;

    /**
     * @var string
     */
    protected const FIELD_NAME_FK_SALES_ORDER = 'fk_sales_order';

    /**
     * @var string
     */
    protected const FIELD_NAME_NAME = 'name';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithoutVoucherDiscount(): QuoteTransfer
    {
        return $this->createQuoteTransferWithItems([
            [
                ItemTransfer::QUANTITY => static::DEFAULT_ITEM_QUANTITY,
            ],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithVoucherDiscount(): QuoteTransfer
    {
        return $this->createQuoteTransferWithItems([
            [
                ItemTransfer::QUANTITY => static::DEFAULT_ITEM_QUANTITY,
            ],
        ])->addVoucherDiscount((new DiscountTransfer())->setVoucherCode(static::VOUCHER_CODE));
    }

    /**
     * @param array<int> $relatedStoreIds
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function createDiscountConfiguratorTransfer(array $relatedStoreIds = []): DiscountConfiguratorTransfer
    {
        return (new DiscountConfiguratorBuilder())->withDiscountGeneral([
            DiscountGeneralTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
            DiscountGeneralTransfer::IS_ACTIVE => true,
            DiscountGeneralTransfer::IS_EXCLUSIVE => true,
            DiscountGeneralTransfer::STORE_RELATION => (new StoreRelationTransfer())->setIdStores($relatedStoreIds),
        ])->withDiscountCondition([
            DiscountConditionTransfer::MINIMUM_ITEM_AMOUNT => 1,
            DiscountConditionTransfer::DECISION_RULE_QUERY_STRING => 'sku = "123"',
        ])->withDiscountCalculator([
            DiscountCalculatorTransfer::AMOUNT => 10,
            DiscountCalculatorTransfer::COLLECTOR_STRATEGY_TYPE => DiscountConstants::DISCOUNT_COLLECTOR_STRATEGY_QUERY_STRING,
            DiscountCalculatorTransfer::CALCULATOR_PLUGIN => DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED,
            DiscountCalculatorTransfer::COLLECTOR_QUERY_STRING => 'sku = "123"',
        ])->build();
    }

    /**
     * @param int $idDiscount
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount|null
     */
    public function findDiscountEntityById(int $idDiscount): ?SpyDiscount
    {
        return $this->getDiscountQuery()->findOneByIdDiscount($idDiscount);
    }

    /**
     * @param int $idDiscount
     *
     * @return array<\Orm\Zed\Discount\Persistence\SpyDiscountStore>
     */
    public function getDiscountStoreEntityCollectionByIdDiscount(int $idDiscount): array
    {
        return $this->getDiscountStoreQuery()
            ->findByFkDiscount($idDiscount)
            ->getData();
    }

    /**
     * @param array $itemsData
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithItems(array $itemsData = []): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer->setStore($this->haveStore([
            StoreTransfer::NAME => static::STORE_DE,
        ]));

        $quoteTransfer->setCurrency(
            (new CurrencyTransfer())->setCode(static::CURRENCY_EUR),
        );

        foreach ($itemsData as $itemData) {
            $itemTransfer = (new ItemBuilder($itemData))->build();
            $quoteTransfer->addItem($itemTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param array $discountOverride
     * @param int $discountMinimumItemAmount
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function createDiscountTransferWithDiscountVoucherPool(
        array $discountOverride = [],
        int $discountMinimumItemAmount = 1
    ): DiscountTransfer {
        $discountVoucherPoolId = $this->haveDiscountVoucherPool(
            $this->generateRandomString(static::DISCOUNT_VOUCHER_POOL_NAME_LENGTH),
        );

        $discountOverride += [
            DiscountTransfer::FK_DISCOUNT_VOUCHER_POOL => $discountVoucherPoolId,
        ];
        $discountTransfer = $this->haveDiscountWithMinimumItemAmount($discountOverride, $discountMinimumItemAmount);

        $this->haveDiscountStore(
            $this->haveStore([StoreTransfer::NAME => static::STORE_DE]),
            $discountTransfer,
        );

        $currencyTransfer = $this->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_EUR]);
        $this->haveDiscountAmount([
            DiscountMoneyAmountTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
            DiscountMoneyAmountTransfer::GROSS_AMOUNT => $discountTransfer->getAmount(),
            DiscountMoneyAmountTransfer::FK_DISCOUNT => $discountTransfer->getIdDiscount(),
        ]);

        return $discountTransfer;
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    public function createDiscountRepository(): DiscountRepositoryInterface
    {
        return new DiscountRepository();
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherTransfer|null
     */
    public function findDiscountVoucher(string $code): ?DiscountVoucherTransfer
    {
        $discountVoucherEntity = $this->getDiscountVoucherQuery()->findOneByCode($code);

        if ($discountVoucherEntity === null) {
            return null;
        }

        return (new DiscountVoucherTransfer())->fromArray($discountVoucherEntity->toArray(), true);
    }

    /**
     * @param int $idSalesDiscount
     * @param string $code
     * @param string $codepoolName
     *
     * @return void
     */
    public function haveSalesDiscountCode(
        int $idSalesDiscount,
        string $code,
        string $codepoolName
    ): void {
        $salesDiscountCodeEntity = SpySalesDiscountCodeQuery::create()
            ->filterByFkSalesDiscount($idSalesDiscount)
            ->findOneOrCreate();
        $salesDiscountCodeEntity->setCode($code)
            ->setCodepoolName($codepoolName)
            ->save();
    }

    /**
     * @param array<string, mixed> $override
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherTransfer
     */
    public function createDiscountVoucher(array $override = []): DiscountVoucherTransfer
    {
        $discountGeneralTransfer = $this->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
        ]);
        $override[DiscountTransfer::ID_DISCOUNT] = $discountGeneralTransfer->getIdDiscount();
        $discountVoucherTransfer = $this->haveGeneratedVoucherCodes($override);

        return $discountVoucherTransfer;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getSalesDiscountEntities(): ObjectCollection
    {
        return $this->getSalesDiscountQuery()->find();
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection
     */
    public function getSalesDiscountCodeEntities(): ObjectCollection
    {
        return $this->getSalesDiscountCodeQuery()->find();
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer|null $expenseTransfer
     * @param int|null $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscount
     */
    public function createSalesDiscountEntities(
        SaveOrderTransfer $saveOrderTransfer,
        DiscountVoucherTransfer $discountVoucherTransfer,
        ?ExpenseTransfer $expenseTransfer = null,
        ?int $idSalesOrderItem = null
    ): SpySalesDiscount {
        $salesDiscountData = [
            static::FIELD_NAME_FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrderOrFail(),
            static::FIELD_NAME_NAME => 'test discount',
        ];

        if ($expenseTransfer) {
            $salesDiscountData['fk_sales_expense'] = $expenseTransfer->getIdSalesExpenseOrFail();
        }

        if ($idSalesOrderItem) {
            $salesDiscountData['fk_sales_order_item'] = $idSalesOrderItem;
        }

        $salesDiscountEntity = $this->haveSalesDiscount($salesDiscountData);
        $this->haveSalesDiscountCode(
            $salesDiscountEntity->getIdSalesDiscount(),
            $discountVoucherTransfer->getCodeOrFail(),
            '',
        );

        return $salesDiscountEntity;
    }

    /**
     * @return void
     */
    public function ensureSalesDiscountTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesDiscountQuery());
    }

    /**
     * @return void
     */
    public function ensureSalesDiscountCodeTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesDiscountCodeQuery());
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected function getDiscountQuery(): SpyDiscountQuery
    {
        return SpyDiscountQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery
     */
    protected function getDiscountStoreQuery(): SpyDiscountStoreQuery
    {
        return SpyDiscountStoreQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    protected function getDiscountVoucherQuery(): SpyDiscountVoucherQuery
    {
        return SpyDiscountVoucherQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountQuery
     */
    protected function getSalesDiscountQuery(): SpySalesDiscountQuery
    {
        return SpySalesDiscountQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesDiscountCodeQuery
     */
    protected function getSalesDiscountCodeQuery(): SpySalesDiscountCodeQuery
    {
        return SpySalesDiscountCodeQuery::create();
    }
}
