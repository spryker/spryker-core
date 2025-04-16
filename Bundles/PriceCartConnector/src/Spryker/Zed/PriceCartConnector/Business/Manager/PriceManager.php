<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Business\Builder\ItemIdentifierBuilderInterface;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface;

class PriceManager implements PriceManagerInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CART_ITEM_CAN_NOT_BE_PRICED = 'Cart item "%s" can not be priced.';

    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $defaultPriceType;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected PriceCartToPriceProductInterface $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected PriceCartToPriceInterface $priceFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface
     */
    protected PriceProductFilterInterface $priceProductFilter;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface
     */
    protected PriceCartConnectorToPriceProductServiceInterface $priceProductService;

    /**
     * @var list<\Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\PriceProductExpanderPluginInterface>
     */
    protected array $priceProductExpanderPlugins;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Business\Builder\ItemIdentifierBuilderInterface
     */
    protected ItemIdentifierBuilderInterface $itemIdentifierBuilder;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\Business\Filter\PriceProductFilterInterface $priceProductFilter
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToPriceProductServiceInterface $priceProductService
     * @param array<\Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\PriceProductExpanderPluginInterface> $priceProductExpanderPlugins
     * @param \Spryker\Zed\PriceCartConnector\Business\Builder\ItemIdentifierBuilderInterface $itemIdentifierBuilder
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade,
        PriceProductFilterInterface $priceProductFilter,
        PriceCartConnectorToPriceProductServiceInterface $priceProductService,
        array $priceProductExpanderPlugins,
        ItemIdentifierBuilderInterface $itemIdentifierBuilder
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->priceProductFilter = $priceProductFilter;
        $this->priceProductService = $priceProductService;
        $this->priceProductExpanderPlugins = $priceProductExpanderPlugins;
        $this->itemIdentifierBuilder = $itemIdentifierBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param bool|null $ignorePriceMissingException
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addPriceToItems(CartChangeTransfer $cartChangeTransfer, ?bool $ignorePriceMissingException = false)
    {
        $cartChangeTransfer->setQuote(
            $this->setQuotePriceMode($cartChangeTransfer->getQuote()),
        );
        $priceMode = $cartChangeTransfer->getQuote()->getPriceMode();

        $priceProductFilterTransfers = $this->createPriceProductFilterTransfers($cartChangeTransfer);
        $priceProductTransfers = $this->priceProductFacade->getValidPrices($priceProductFilterTransfers);
        $priceProductTransfers = $this->executePriceProductExpanderPlugins($priceProductTransfers, $cartChangeTransfer);

        $priceProductTransfersIndexedByItemIdentifier = $this->getPriceProductTransfersIndexedByItemIdentifier(
            $cartChangeTransfer,
            $priceProductTransfers,
            $priceProductFilterTransfers,
            $ignorePriceMissingException,
        );

        foreach ($cartChangeTransfer->getItems() as $key => $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer, (string)$key);

            $itemTransfer = $this->setOriginUnitPrices(
                $itemTransfer,
                $priceProductTransfersIndexedByItemIdentifier[$itemIdentifier],
                $priceMode,
            );

            if ($this->hasForcedUnitGrossPrice($itemTransfer)) {
                continue;
            }

            if ($this->hasSourceUnitPrices($itemTransfer)) {
                $itemTransfer = $this->applySourceUnitPrices($itemTransfer);

                continue;
            }

            $itemTransfer = $this->applyOriginUnitPrices($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     * @param bool|null $ignorePriceMissingException
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function resolveProductPriceByPriceProductFilter(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceFilterTransfer,
        ?bool $ignorePriceMissingException = false
    ): PriceProductTransfer {
        $priceProductTransfer = $this->priceProductService->resolveProductPriceByPriceProductFilter(
            $priceProductTransfers,
            $priceFilterTransfer,
        );

        if (!$priceProductTransfer) {
            if ($ignorePriceMissingException === true) {
                return (new PriceProductTransfer())->setMoneyValue(new MoneyValueTransfer());
            }

            throw new PriceMissingException(
                sprintf(
                    static::ERROR_MESSAGE_CART_ITEM_CAN_NOT_BE_PRICED,
                    $priceFilterTransfer->getSku(),
                ),
            );
        }

        return $priceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PriceProductFilterTransfer>
     */
    protected function createPriceProductFilterTransfers(CartChangeTransfer $cartChangeTransfer): array
    {
        $priceProductFilterTransfers = [];
        foreach ($cartChangeTransfer->getItems() as $key => $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer, (string)$key);
            if (array_key_exists($itemIdentifier, $priceProductFilterTransfers)) {
                continue;
            }

            $priceProductFilterTransfers[$itemIdentifier] = $this->priceProductFilter->createPriceProductFilterTransfer(
                $cartChangeTransfer,
                $itemTransfer,
            );
        }

        return $priceProductFilterTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitPrices(
        ItemTransfer $itemTransfer,
        PriceProductTransfer $priceProductTransfer,
        string $priceMode
    ): ItemTransfer {
        $itemTransfer->setPriceProduct($priceProductTransfer);
        if ($priceMode === $this->getNetPriceModeIdentifier()) {
            return $this->setOriginUnitNetPrice($itemTransfer, $priceProductTransfer);
        }

        return $this->setOriginUnitGrossPrice($itemTransfer, $priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitGrossPrice(
        ItemTransfer $itemTransfer,
        PriceProductTransfer $priceProductTransfer
    ): ItemTransfer {
        $itemTransfer->setOriginUnitNetPrice(0);
        $itemTransfer->setOriginUnitGrossPrice($priceProductTransfer->getMoneyValue()->getGrossAmount());
        $itemTransfer->setSumNetPrice(0);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitNetPrice(
        ItemTransfer $itemTransfer,
        PriceProductTransfer $priceProductTransfer
    ): ItemTransfer {
        $itemTransfer->setOriginUnitNetPrice($priceProductTransfer->getMoneyValue()->getNetAmount());
        $itemTransfer->setOriginUnitGrossPrice(0);
        $itemTransfer->setSumGrossPrice(0);

        return $itemTransfer;
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier(): string
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applySourceUnitPrices(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getSourceUnitNetPrice() !== null) {
            $itemTransfer->setUnitNetPrice($itemTransfer->getSourceUnitNetPrice());
            $itemTransfer->setUnitGrossPrice(0);
        }

        if ($itemTransfer->getSourceUnitGrossPrice() !== null) {
            $itemTransfer->setUnitNetPrice(0);
            $itemTransfer->setUnitGrossPrice($itemTransfer->getSourceUnitGrossPrice());
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applyOriginUnitPrices(ItemTransfer $itemTransfer)
    {
        $itemTransfer->setUnitNetPrice($itemTransfer->getOriginUnitNetPrice());
        $itemTransfer->setUnitGrossPrice($itemTransfer->getOriginUnitGrossPrice());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasSourceUnitPrices(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getSourceUnitGrossPrice() !== null) {
            return true;
        }

        if ($itemTransfer->getSourceUnitNetPrice() !== null) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated Will be removed with a next major release
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasForcedUnitGrossPrice(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getForcedUnitGrossPrice() && $itemTransfer->getUnitGrossPrice() !== null) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuotePriceMode(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getPriceMode()) {
            $quoteTransfer->setPriceMode($this->priceFacade->getDefaultPriceMode());
        }

        return $quoteTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function executePriceProductExpanderPlugins(array $priceProductTransfers, CartChangeTransfer $cartChangeTransfer): array
    {
        foreach ($this->priceProductExpanderPlugins as $priceProductExpanderPlugin) {
            $priceProductTransfers = $priceProductExpanderPlugin->expandPriceProductTransfers($priceProductTransfers, $cartChangeTransfer);
        }

        return $priceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param list<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<string, \Generated\Shared\Transfer\PriceProductFilterTransfer> $priceProductFilterTransfers
     * @param bool|null $ignorePriceMissingException
     *
     * @return array<string, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getPriceProductTransfersIndexedByItemIdentifier(
        CartChangeTransfer $cartChangeTransfer,
        array $priceProductTransfers,
        array $priceProductFilterTransfers,
        ?bool $ignorePriceMissingException = false
    ): array {
        $priceProductTransfersIndexedByItemIdentifier = [];
        foreach ($cartChangeTransfer->getItems() as $key => $itemTransfer) {
            $itemIdentifier = $this->getItemIdentifier($itemTransfer, (string)$key);
            if (array_key_exists($itemIdentifier, $priceProductTransfersIndexedByItemIdentifier)) {
                continue;
            }

            $priceProductTransfersIndexedByItemIdentifier[$itemIdentifier] = $this->resolveProductPriceByPriceProductFilter(
                $priceProductTransfers,
                $priceProductFilterTransfers[$itemIdentifier],
                $ignorePriceMissingException,
            );
        }

        return $priceProductTransfersIndexedByItemIdentifier;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $fallbackIdentifier
     *
     * @return string
     */
    protected function getItemIdentifier(ItemTransfer $itemTransfer, string $fallbackIdentifier): string
    {
        return $this->itemIdentifierBuilder->buildItemIdentifier($itemTransfer) ?: $fallbackIdentifier;
    }
}
