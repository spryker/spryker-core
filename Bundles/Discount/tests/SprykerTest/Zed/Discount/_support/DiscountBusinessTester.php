<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
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
 *
 * @SuppressWarnings(PHPMD)
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
}
