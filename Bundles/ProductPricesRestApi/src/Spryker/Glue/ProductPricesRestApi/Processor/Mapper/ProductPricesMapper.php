<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestPriceProductTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface;

class ProductPricesMapper implements ProductPricesMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var string|null
     */
    protected static $currentPriceMode;

    /**
     * @var string|null
     */
    protected static $grossPriceModeIdentifier;

    /**
     * @var string|null
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Glue\ProductPricesRestApiExtension\Dependency\Plugin\RestProductPricesAttributesMapperPluginInterface[]
     */
    protected $restProductPricesAttributesMapperPlugins;

    /**
     * @var \Generated\Shared\Transfer\RestCurrencyTransfer
     */
    protected static $restCurrencyTransfer;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface $priceClient
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface $currencyClient
     * @param \Spryker\Glue\ProductPricesRestApiExtension\Dependency\Plugin\RestProductPricesAttributesMapperPluginInterface[] $restProductPricesAttributesMapperPlugins
     */
    public function __construct(
        ProductPricesRestApiToPriceClientInterface $priceClient,
        ProductPricesRestApiToCurrencyClientInterface $currencyClient,
        array $restProductPricesAttributesMapperPlugins
    ) {
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->restProductPricesAttributesMapperPlugins = $restProductPricesAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapCurrentProductPriceTransferToRestProductPricesAttributesTransfer(
        CurrentProductPriceTransfer $currentProductPriceTransfer
    ): RestProductPricesAttributesTransfer {
        /** @todo: This mapping should be changed after decision about price filtering in api. */
        $productPricesRestAttributesTransfer = (new RestProductPricesAttributesTransfer())
            ->setPrice($currentProductPriceTransfer->getPrice());
        foreach ($currentProductPriceTransfer->getPrices() as $priceType => $amount) {
            $restProductPriceAttributesTransfer = $this->getRestProductPriceAttributesTransfer($priceType, $amount);
            $restProductPriceAttributesTransfer = $this->executeRestProductPriceAttributesMapperPlugins(
                $currentProductPriceTransfer,
                $restProductPriceAttributesTransfer
            );

            $productPricesRestAttributesTransfer->addPrice($restProductPriceAttributesTransfer);
        }

        return $productPricesRestAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemsAttributesTransfer
     */
    public function mapWishlistItemTransferPricesToRestWishlistItemsAttributesTransfer(
        WishlistItemTransfer $wishlistItemTransfer,
        RestWishlistItemsAttributesTransfer $restWishlistItemsAttributesTransfer
    ): RestWishlistItemsAttributesTransfer {
        $restPriceProductTransfers = new ArrayObject();

        foreach ($wishlistItemTransfer->getPrices() as $priceProductTransfer) {

            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
            $restPriceProductTransfer = (new RestPriceProductTransfer())
                ->fromArray($moneyValueTransfer->toArray(), true);

            if ($priceProductTransfer->getPriceType()) {
                /** @var \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer */
                $priceTypeTransfer = $priceProductTransfer->getPriceType();
                $restPriceProductTransfer->setPriceTypeName($priceTypeTransfer->getName());
            }

            $restPriceProductTransfers->append($restPriceProductTransfer);
        }

        return $restWishlistItemsAttributesTransfer->setPrices($restPriceProductTransfers);
    }

    /**
     * @param string $priceType
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    protected function getRestProductPriceAttributesTransfer(string $priceType, int $amount): RestProductPriceAttributesTransfer
    {
        $restProductPriceAttributesTransfer = new RestProductPriceAttributesTransfer();

        $restProductPriceAttributesTransfer->setPriceTypeName($priceType);
        $restProductPriceAttributesTransfer->setCurrency($this->getRestCurrencyTransfer());
        if ($this->getCurrentPriceMode() === $this->getGrossPriceModeIdentifier()) {
            $restProductPriceAttributesTransfer->setGrossAmount($amount);

            return $restProductPriceAttributesTransfer;
        }
        if ($this->getCurrentPriceMode() === $this->getNetPriceModeIdentifier()) {
            $restProductPriceAttributesTransfer->setNetAmount($amount);

            return $restProductPriceAttributesTransfer;
        }

        return $restProductPriceAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    public function executeRestProductPriceAttributesMapperPlugins(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPriceAttributesTransfer {
        foreach ($this->restProductPricesAttributesMapperPlugins as $restProductPricesAttributesMapperPlugin) {
            $restProductPriceAttributesTransfer = $restProductPricesAttributesMapperPlugin->map(
                $currentProductPriceTransfer,
                $restProductPriceAttributesTransfer
            );
        }

        return $restProductPriceAttributesTransfer;
    }

    /**
     * @return string
     */
    protected function getCurrentPriceMode(): string
    {
        if (!static::$currentPriceMode) {
            static::$currentPriceMode = $this->priceClient->getCurrentPriceMode();
        }

        return static::$currentPriceMode;
    }

    /**
     * @return string
     */
    protected function getGrossPriceModeIdentifier(): string
    {
        if (static::$grossPriceModeIdentifier === null) {
            static::$grossPriceModeIdentifier = $this->priceClient->getGrossPriceModeIdentifier();
        }

        return static::$grossPriceModeIdentifier;
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier(): string
    {
        if (static::$netPriceModeIdentifier === null) {
            static::$netPriceModeIdentifier = $this->priceClient->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCurrencyTransfer
     */
    protected function getRestCurrencyTransfer(): RestCurrencyTransfer
    {
        if (static::$restCurrencyTransfer === null) {
            static::$restCurrencyTransfer = (new RestCurrencyTransfer())
                ->fromArray($this->currencyClient->getCurrent()->toArray(), true);
        }

        return static::$restCurrencyTransfer;
    }
}
