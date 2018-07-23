<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

class ImagePageDataLoaderPlugin implements ProductPageDataLoaderPluginInterface
{

    /**
     * @param ProductPageLoadTransfer $loadTransfer
     *
     * @return void
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $payloadTransfers = $this->setProductImages($loadTransfer->getProductAbstractIds(), $loadTransfer->getPayloadTransfers());

        $loadTransfer->setPayloadTransfers($payloadTransfers);
    }

    /**
     * @return string
     */
    public function getProductPageType()
    {
        return 'image';
    }

    /**
     * @param array $productAbstractIds
     * @param array  $payloadTransfers
     *
     * @return array
     */
    protected function setProductImages(array $productAbstractIds, array $payloadTransfers): array
    {
        //TODO check PriceProductCriteriaBuilder::getCurrencyFromFilter()
        $idDefaultCurrencyCode = 93;

        //TODO move this to query container
        $query = SpyPriceProductStoreQuery::create()
            ->filterByFkCurrency($idDefaultCurrencyCode)
            ->joinWithStore()
            ->usePriceProductQuery()
                ->filterByFkProductAbstract_In($productAbstractIds)
                ->joinWithPriceType()
            ->endUse()
            ->withColumn(SpyStoreTableMap::COL_NAME, 'store_name')
            ->withColumn(SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT, 'id_product_abstract')
            ->withColumn(SpyPriceProductStoreTableMap::COL_GROSS_PRICE, 'GROSS_PRICE')
            ->withColumn(SpyPriceProductStoreTableMap::COL_NET_PRICE, 'NET_PRICE')
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'price_type')
            ->select(['id_product_abstract', 'GROSS_PRICE', 'NET_PRICE', 'price_type'])
        ;

        $prices = $query->find();
        // TODO add price dimension plugin logic here

        foreach ($prices as $price) {
            if (isset($payloadTransfers[$price['id_product_abstract']])) {
                // TODO PriceMode should come from PriceConfig
                $priceWithStore = [];
                if ($payloadTransfers[$price['id_product_abstract']]->getPrice()) {
                    $priceWithStore = $payloadTransfers[$price['id_product_abstract']]->getPrice();
                }
                
                $priceWithStore[$price['store_name']][] = $price['GROSS_PRICE'];
                $payloadTransfers[$price['id_product_abstract']]->setPrice($priceWithStore);
            }
        }
    }
}
