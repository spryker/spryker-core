<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class PriceProductValidator implements PriceProductValidatorInterface
{
    /**
     * @var string
     */
    public const CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY = 'cart.pre.check.price.failed';

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    protected $priceProductFilter;

    /**
     * @var \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected PriceCartConnectorConfig $priceCartConnectorConfig;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface $priceProductFilter
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfig
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceProductFilterInterface $priceProductFilter,
        PriceCartConnectorConfig $priceCartConnectorConfig
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceProductFilter = $priceProductFilter;
        $this->priceCartConnectorConfig = $priceCartConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validatePrices(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $priceProductFilterTransfers = $this->createPriceProductFilters($cartChangeTransfer);
        $validPriceProductTransfers = $this->priceProductFacade->getValidPrices($priceProductFilterTransfers);

        if (!$this->priceCartConnectorConfig->isZeroPriceEnabledForCartActions()) {
            $validPriceProductTransfers = $this->filterOutZeroPriceProductTransfers($validPriceProductTransfers, $priceProductFilterTransfers);
        }

        return $this->checkProductWithoutPricesRestriction($validPriceProductTransfers, $cartChangeTransfer, $cartPreCheckResponseTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterOutZeroPriceProductTransfers(array $priceProductTransfers, array $priceProductFilterTransfers): array
    {
        $indexedPriceProductFilterTransfers = $this->getPriceProductFilterTransfersIndexedBySku($priceProductFilterTransfers);
        $validPriceProductTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceProductFilter = $indexedPriceProductFilterTransfers[$priceProductTransfer->getSkuProduct()];
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            $priceMode = $priceProductFilter->getPriceMode();
            $priceValue = $this->getPriceValueByPriceMode($moneyValueTransfer, $priceMode);

            if (!$priceValue) {
                continue;
            }

            $validPriceProductTransfers[] = $priceProductTransfer;
        }

        return $validPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $priceMode
     *
     * @return int|null
     */
    protected function getPriceValueByPriceMode(MoneyValueTransfer $moneyValueTransfer, string $priceMode): ?int
    {
        if ($priceMode === static::PRICE_MODE_GROSS) {
            return $moneyValueTransfer->getGrossAmount();
        }

        return $moneyValueTransfer->getNetAmount();
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PriceProductFilterTransfer>
     */
    protected function getPriceProductFilterTransfersIndexedBySku(array $priceProductFilterTransfers): array
    {
        $indexedPriceProductFilterTransfers = [];

        foreach ($priceProductFilterTransfers as $priceProductFilterTransfer) {
            $indexedPriceProductFilterTransfers[$priceProductFilterTransfer->getSku()] = $priceProductFilterTransfer;
        }

        return $indexedPriceProductFilterTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkProductWithoutPricesRestriction(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        $productWithoutPriceSkus = $this->getProductWithoutPriceSkus($priceProductTransfers, $cartChangeTransfer->getItems()->getArrayCopy());

        if ($productWithoutPriceSkus) {
            return $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->addMessage($this->createMessage($this->getFirstNotValidSku($productWithoutPriceSkus)));
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param array<string> $productWithoutPriceSkus
     *
     * @return string
     */
    protected function getFirstNotValidSku(array $productWithoutPriceSkus): string
    {
        return array_shift($productWithoutPriceSkus);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(static::CART_PRE_CHECK_PRICE_FAILED_TRANSLATION_KEY)
            ->setParameters(['%sku%' => $sku]);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $items
     *
     * @return array<string>
     */
    protected function getProductWithoutPriceSkus(array $priceProductTransfers, array $items): array
    {
        $totalItemSkus = array_map(function (ItemTransfer $item) {
            return $item->getSku();
        }, $items);

        $validProductSkus = array_map(function (PriceProductTransfer $priceProductTransfer) {
            return $priceProductTransfer->getSkuProduct();
        }, $priceProductTransfers);

        return array_diff($totalItemSkus, $validProductSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductFilterTransfer>
     */
    protected function createPriceProductFilters(CartChangeTransfer $cartChangeTransfer): array
    {
        $priceProductFilters = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $priceProductFilters[] = $this->priceProductFilter->createPriceProductFilterTransfer($cartChangeTransfer, $itemTransfer);
        }

        return $priceProductFilters;
    }
}
