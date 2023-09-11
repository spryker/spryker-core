<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Plugin\Catalog\ResultFormatter;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductClientInterface;
use Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductStorageClientInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\GroupedResultFormatterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface;

/**
 * @method \Spryker\Client\CatalogPriceProductConnector\CatalogPriceProductConnectorFactory getFactory()
 */
class CurrencyAwareCatalogSearchHttpResultFormatterPlugin extends AbstractPlugin implements ResultFormatterPluginInterface, GroupedResultFormatterPluginInterface
{
    /**
     * @var string
     */
    protected const GROUP_NAME = 'suggestionByType';

    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface
     */
    protected ResultFormatterPluginInterface $productResultFormatterPlugin;

    /**
     * @var array<int, \Generated\Shared\Transfer\CurrentProductPriceTransfer>
     */
    protected array $productPriceTransfersByIdAbstractProduct = [];

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface $productResultFormatterPlugin
     */
    public function __construct(ResultFormatterPluginInterface $productResultFormatterPlugin)
    {
        $this->productResultFormatterPlugin = $productResultFormatterPlugin;
    }

    /**
     * {@inheritDoc}
     * - Formats prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer|\Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return array<int, mixed>
     */
    public function formatResult($searchResult, array $requestParameters = []): array
    {
        if (!$this->isPriceProductDimensionEnabled()) {
            return $this->formatSearchResultWithoutPriceDimensions($searchResult);
        }

        return $this->formatSearchResultWithPriceDimensions($searchResult);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->productResultFormatterPlugin->getName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getGroupName(): string
    {
        return static::GROUP_NAME;
    }

    /**
     * @return bool
     */
    protected function isPriceProductDimensionEnabled(): bool
    {
        return defined('\Spryker\Shared\PriceProduct\PriceProductConstants::PRICE_DIMENSION_DEFAULT');
    }

    /**
     * Fallback method to work with PriceProduct module without price dimensions support.
     *
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer|\Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     *
     * @return array<int, mixed>
     */
    protected function formatSearchResultWithoutPriceDimensions(SearchHttpResponseTransfer|SuggestionsSearchHttpResponseTransfer $searchResult): array
    {
        $priceProductClient = $this->getFactory()->getPriceProductClient();

        $products = $this->productResultFormatterPlugin->formatResult($searchResult);

        foreach ($products as $key => $product) {
            $currentProductPriceTransfer = $priceProductClient->resolveProductPrice(
                $this->mapSearchResultPricesToPriceMap($product['prices']),
            );

            $products[$key]['price'] = $currentProductPriceTransfer->getPrice();
            $products[$key]['prices'] = $currentProductPriceTransfer->getPrices();
        }

        return $products;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer|\Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $searchResult
     *
     * @return array<int, mixed>
     */
    protected function formatSearchResultWithPriceDimensions(SearchHttpResponseTransfer|SuggestionsSearchHttpResponseTransfer $searchResult): array
    {
        $products = $this->productResultFormatterPlugin->formatResult($searchResult);

        $priceProductClient = $this->getFactory()->getPriceProductClient();
        $priceProductStorageClient = $this->getFactory()->getPriceProductStorageClient();

        foreach ($products as $key => $product) {
            $currentProductPriceTransfer = $this->getPriceProductAbstractTransfers(
                $product['id_product_abstract'],
                $priceProductClient,
                $priceProductStorageClient,
            );

            $products[$key]['price'] = $currentProductPriceTransfer->getPrice();
            $products[$key]['prices'] = $currentProductPriceTransfer->getPrices();
        }

        return $products;
    }

    /**
     * @param int $idProductAbstract
     * @param \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductStorageClientInterface $priceProductStorageClient
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    protected function getPriceProductAbstractTransfers(
        int $idProductAbstract,
        CatalogPriceProductConnectorToPriceProductClientInterface $priceProductClient,
        CatalogPriceProductConnectorToPriceProductStorageClientInterface $priceProductStorageClient
    ): CurrentProductPriceTransfer {
        if (isset($this->productPriceTransfersByIdAbstractProduct[$idProductAbstract])) {
            return $this->productPriceTransfersByIdAbstractProduct[$idProductAbstract];
        }

        $priceProductTransfersFromStorage = $priceProductStorageClient->getPriceProductAbstractTransfers($idProductAbstract);
        $currentProductPriceTransfer = $priceProductClient->resolveProductPriceTransfer($priceProductTransfersFromStorage);

        $this->productPriceTransfersByIdAbstractProduct[$idProductAbstract] = $currentProductPriceTransfer;

        return $this->productPriceTransfersByIdAbstractProduct[$idProductAbstract];
    }

    /**
     * @param array<string, mixed> $prices
     *
     * @return array<string, mixed>
     */
    protected function mapSearchResultPricesToPriceMap(array $prices): array
    {
        $priceMap = [];

        foreach ($prices as $price) {
            $priceMap[strtoupper($price['currency'])] = [
                'priceDataByPriceType' => [
                    'DEFAULT' => null,
                ],
                'GROSS_MODE' => [
                    'DEFAULT' => $price['price_gross'] ?? null,
                ],
                'priceData' => null,
                'NET_MODE' => [
                    'DEFAULT' => $price['price_net'] ?? null,
                ],
            ];
        }

        return $priceMap;
    }
}
