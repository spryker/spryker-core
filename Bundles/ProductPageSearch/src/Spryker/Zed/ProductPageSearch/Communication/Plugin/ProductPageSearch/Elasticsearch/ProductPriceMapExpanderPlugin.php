<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\ProductPageSearch\Elasticsearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductPriceMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    protected const KEY_PRICE = 'price';
    protected const KEY_PRICES = 'prices';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ) {
        $price = $productData[static::KEY_PRICE];

        if ($price === null) {
            $this->setPricesByType($pageMapBuilder, $pageMapTransfer, $productData);

            return $pageMapTransfer;
        }

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::KEY_PRICE, $price)
            ->addIntegerSort($pageMapTransfer, static::KEY_PRICE, $price)
            ->addIntegerFacet($pageMapTransfer, static::KEY_PRICE, $price);

        $this->setPricesByType($pageMapBuilder, $pageMapTransfer, $productData);

        return $pageMapTransfer;
    }

    /**
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $productData
     *
     * @return void
     */
    protected function setPricesByType(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $productData)
    {
        foreach ($productData[static::KEY_PRICES] as $currencyIsoCode => $pricesByPriceMode) {
            foreach (ProductPageSearchConfig::PRICE_MODES as $priceMode) {
                if (!isset($pricesByPriceMode[$priceMode])) {
                    continue;
                }
                foreach ($pricesByPriceMode[$priceMode] as $priceType => $price) {
                    $facetName = $this->getFactory()->getCatalogPriceProductConnectorClient()->buildPricedIdentifierFor($priceType, $currencyIsoCode, $priceMode);
                    $pageMapBuilder->addIntegerFacet($pageMapTransfer, $facetName, $price);
                    $pageMapBuilder->addIntegerSort($pageMapTransfer, $facetName, $price);
                }
            }
        }

        $pageMapBuilder->addSearchResultData($pageMapTransfer, static::KEY_PRICES, $productData[static::KEY_PRICES]);
    }
}
