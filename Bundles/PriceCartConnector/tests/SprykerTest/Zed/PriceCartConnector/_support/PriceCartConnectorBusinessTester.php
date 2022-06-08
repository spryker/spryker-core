<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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
class PriceCartConnectorBusinessTester extends Actor
{
    use _generated\PriceCartConnectorBusinessTesterActions;

    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @param array $itemsData
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteWithItems(array $itemsData, CurrencyTransfer $currencyTransfer): QuoteTransfer
    {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::STORE_DE]);
        $itemsTransfers = $this->createItemTransfersBySkuAndPriceCollection($itemsData, $currencyTransfer, $storeTransfer);

        return (new QuoteBuilder([
            QuoteTransfer::STORE => $storeTransfer,
            QuoteTransfer::CURRENCY => $currencyTransfer,
            QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
        ]))->build()->setItems(new ArrayObject($itemsTransfers));
    }

    /**
     * @param array $itemsData
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function createItemTransfersBySkuAndPriceCollection(
        array $itemsData,
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $itemsTransfers = [];
        foreach ($itemsData as $sku => $itemPrice) {
            $productConcreteTransfer = $this->haveProduct([
                ProductConcreteTransfer::SKU => $sku,
                ProductConcreteTransfer::ABSTRACT_SKU => $sku,
            ]);

            if ($itemPrice !== null) {
                $this->havePriceProduct([
                    PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
                    PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::NET_AMOUNT => $itemPrice,
                        MoneyValueTransfer::GROSS_AMOUNT => $itemPrice,
                        MoneyValueTransfer::STORE => $storeTransfer->getName(),
                        MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    ],
                ]);
            }

            $itemsTransfers[] = (new ItemTransfer())->fromArray($productConcreteTransfer->toArray(), true);
        }

        return $itemsTransfers;
    }
}
