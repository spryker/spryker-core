<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ProductPackagingUnitAmountBuilder;
use Generated\Shared\DataBuilder\ProductPackagingUnitBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class PriceChangeHelper extends Module
{
    protected const DEFAULT_AMOUNT = 10000;
    protected const AMOUNT = 48;
    protected const QUANTITY = 1;
    protected const UNIT_NET_PRICE = 3250;
    protected const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function getCartChangeTransfer(): CartChangeTransfer
    {
        $cartChangeTransfer = (new CartChangeBuilder())->build()
            ->addItem($this->getItemTransfer())
            ->setQuote($this->getQuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemTransfer(): ItemTransfer
    {
        $itemTransfer = (new ItemBuilder())->build()
            ->setProductPackagingUnit($this->getProductPackagingUnitTransfer())
            ->setAmount(static::AMOUNT)
            ->setQuantity(static::QUANTITY)
            ->setUnitNetPrice(static::UNIT_NET_PRICE);

        $itemTransfer
            ->getProductPackagingUnit()
            ->getProductPackagingUnitAmount()
            ->setDefaultAmount(static::DEFAULT_AMOUNT);

        return $itemTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductPackagingUnitAmountTransfer
     */
    protected function getProductPackagingUnitAmountTransfer(): ProductPackagingUnitAmountTransfer
    {
        return (new ProductPackagingUnitAmountBuilder())->build()
            ->setIsVariable(true);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer
     */
    protected function getProductPackagingUnitTransfer(): ProductPackagingUnitTransfer
    {
        return (new ProductPackagingUnitBuilder())->build()
            ->setProductPackagingUnitAmount($this->getProductPackagingUnitAmountTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteTransfer(): QuoteTransfer
    {
        $itemTransfer = (new QuoteBuilder())->build()
            ->setPriceMode(static::PRICE_NET_MODE);

        return $itemTransfer;
    }
}
