<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSalesOrderAmendment\Business\Replacer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface;

class PriceReplacer implements PriceReplacerInterface
{
    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @param \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService
     */
    public function __construct(
        protected PriceProductSalesOrderAmendmentServiceInterface $priceProductSalesOrderAmendmentService
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $originalSalesOrderItemUnitPrice
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function replaceOriginalSalesOrderItemUnitPrice(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        int $originalSalesOrderItemUnitPrice
    ): ItemTransfer {
        $priceMode = $quoteTransfer->getPriceModeOrFail();

        if ($itemTransfer->getUnitNetPrice() !== null && $priceMode === static::PRICE_MODE_NET) {
            $newNetAmount = $this->priceProductSalesOrderAmendmentService->resolveOriginalSalesOrderItemPrice(
                $itemTransfer->getUnitNetPriceOrFail(),
                $originalSalesOrderItemUnitPrice,
                $quoteTransfer,
            );

            $itemTransfer->setUnitNetPrice($newNetAmount);
            $itemTransfer->getPriceProductOrFail()->getMoneyValueOrFail()->setNetAmount($newNetAmount);

            return $itemTransfer;
        }

        if ($itemTransfer->getUnitGrossPrice() !== null && $priceMode === static::PRICE_MODE_GROSS) {
            $newGrossAmount = $this->priceProductSalesOrderAmendmentService->resolveOriginalSalesOrderItemPrice(
                $itemTransfer->getUnitGrossPriceOrFail(),
                $originalSalesOrderItemUnitPrice,
                $quoteTransfer,
            );

            $itemTransfer->setUnitGrossPrice($newGrossAmount);
            $itemTransfer->getPriceProductOrFail()->getMoneyValueOrFail()->setGrossAmount($newGrossAmount);

            return $itemTransfer;
        }

        return $this->setFallbackPriceForNullablePriceProduct(
            $itemTransfer,
            $priceMode,
            $originalSalesOrderItemUnitPrice,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     * @param int $originalSalesOrderItemUnitPrice
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setFallbackPriceForNullablePriceProduct(
        ItemTransfer $itemTransfer,
        string $priceMode,
        int $originalSalesOrderItemUnitPrice
    ): ItemTransfer {
        if ($priceMode === static::PRICE_MODE_NET) {
            $itemTransfer->setUnitNetPrice($originalSalesOrderItemUnitPrice);
            $itemTransfer->getPriceProductOrFail()->getMoneyValueOrFail()->setNetAmount($originalSalesOrderItemUnitPrice);

            return $itemTransfer;
        }

        $itemTransfer->setUnitGrossPrice($originalSalesOrderItemUnitPrice);
        $itemTransfer->getPriceProductOrFail()->getMoneyValueOrFail()->setGrossAmount($originalSalesOrderItemUnitPrice);

        return $itemTransfer;
    }
}
