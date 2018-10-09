<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class PricePageDataLoaderExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $productAbstractSku = $productData['SpyProductAbstract']['sku'];
        $storeName = $productAbstractPageSearchTransfer->getStore();
        $productPayloadTransfer = $this->getProductPayloadTransfer($productData);

        $priceProductTransfers = $this->filterPricesByStore($productPayloadTransfer->getPrices(), $storeName);

        $productAbstractPageSearchTransfer->setPrices(
            $this->groupPrices($priceProductTransfers)
        );

        $productAbstractPageSearchTransfer->setPrice(
            $this->resolveProductPrice($priceProductTransfers, $productAbstractSku, $storeName)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return array
     */
    protected function groupPrices(array $priceProductTransfers): array
    {
        return $this->getFactory()
            ->getPriceProductFacade()
            ->groupPriceProductCollection($priceProductTransfers);
    }

    /**
     * @param array $prices
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function filterPricesByStore(array $prices, string $storeName): array
    {
        return $prices[$storeName] ?? [];
    }

    /**
     * @param array $prices
     * @param string $productAbstractSku
     * @param string $storeName
     *
     * @return int
     */
    protected function resolveProductPrice(array $prices, string $productAbstractSku, string $storeName): int
    {
        $priceProductCriteriaTransfer = $this->createPriceProductCriteriaTransfer($storeName, $productAbstractSku);

        $price = $this->getFactory()
            ->getPriceProductService()
            ->resolveProductPriceByPriceProductCriteria($prices, $priceProductCriteriaTransfer);

        if ($price === null) {
            return 0;
        }

        $priceFacade = $this->getFactory()->getPriceFacade();

        if ($priceFacade->getDefaultPriceMode() === $priceFacade->getGrossPriceModeIdentifier()) {
            return $price->getMoneyValue()->getGrossAmount();
        }

        return $price->getMoneyValue()->getNetAmount();
    }

    /**
     * @param string $storeName
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function createPriceProductCriteriaTransfer(string $storeName, string $productAbstractSku): PriceProductCriteriaTransfer
    {
        $priceFilter = (new PriceProductFilterTransfer())
            ->setSku($productAbstractSku)
            ->setStoreName($storeName);

        return $this->getFactory()->getPriceProductFacade()->buildCriteriaFromFilter($priceFilter);
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayloadTransfer(array $productData): ProductPayloadTransfer
    {
        return $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA];
    }
}
