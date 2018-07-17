<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;


use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

class PricePageDataLoaderPlugin implements ProductPageDataLoaderPluginInterface
{

    /**
     * @param ProductPageLoadTransfer $loadTransfer
     *
     * @return array
     */
    public function loadProductPageData(ProductPageLoadTransfer $loadTransfer)
    {
        // Hacked
        $idCurrentStore = 1;
        $idDefaultCurrencyCode = 88;

        $query = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($idCurrentStore)
            ->filterByFkCurrency($idDefaultCurrencyCode)
        ;
        // TODO add price dimension logic

        return [];
    }

    /**
     * @return string
     */
    public function getProductPageType()
    {
        return 'price';
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    protected function findProductPrice(string $sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?int
    {

        // Find PriceProductConcretes
        $priceProductConcrete = $this->priceProductConcreteReader
            ->findPriceForProductConcrete($sku, $priceProductCriteriaTransfer);

        // foreach priceProductConcretes check MoneyValue
        if ($priceProductConcrete !== null) {
            return $this->getPriceByPriceMode($priceProductConcrete->getMoneyValue(), $priceProductCriteriaTransfer->getPriceMode());
        }

        if ($this->productFacade->hasProductConcrete($sku)) {
            $sku = $this->productFacade->getAbstractSkuFromProductConcrete($sku);
        }

        $priceProductAbstract = $this->priceProductAbstractReader
            ->findPriceForProductAbstract($sku, $priceProductCriteriaTransfer);

        if (!$priceProductAbstract) {
            return null;
        }

        return $this->getPriceByPriceMode($priceProductAbstract->getMoneyValue(), $priceProductCriteriaTransfer->getPriceMode());
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param string $priceMode
     *
     * @return int|null
     */
    protected function getPriceByPriceMode(MoneyValueTransfer $moneyValueTransfer, string $priceMode): ?int
    {
        if ($priceMode === 'NET_MODE') {
            return $moneyValueTransfer->getNetAmount();
        }

        return $moneyValueTransfer->getGrossAmount();
    }
}
