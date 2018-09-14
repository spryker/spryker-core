<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class PricePageDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @var int[]|null Keys are store names, values are store ids.
     */
    protected $idStoreMapBuffer;

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
        $priceFilter = (new PriceProductFilterTransfer())
            ->setSku($productData['SpyProductAbstract']['sku'])
            ->setStoreName($productAbstractPageSearchTransfer->getStore());

        $price = (int)$this->getFactory()->getPriceProductFacade()->findPriceFor($priceFilter);
        $groupedStorePrices = $this->getGroupedStorePrices($productData['SpyProductAbstract']['id_product_abstract'], $productAbstractPageSearchTransfer->getStore());

        $productAbstractPageSearchTransfer->setPrice($price);
        $productAbstractPageSearchTransfer->setPrices($groupedStorePrices);
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return array
     */
    protected function getGroupedStorePrices($idProductAbstract, $storeName)
    {
        $priceProductCollection = $this->getFactory()->getPriceProductFacade()->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract);
        $storePriceProductCollection = $this->filterPriceProductCollectionByStore($priceProductCollection, $storeName);

        return $this->getFactory()->getPriceProductFacade()->groupPriceProductCollection($storePriceProductCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductCollection
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function filterPriceProductCollectionByStore(array $priceProductCollection, $storeName)
    {
        $idStore = $this->getIdStoreByName($storeName);

        $storePriceProductCollection = [];
        foreach ($priceProductCollection as $priceProductTransfer) {
            if ($priceProductTransfer->getMoneyValue()->getFkStore() === $idStore) {
                $storePriceProductCollection[] = $priceProductTransfer;
            }
        }

        return $storePriceProductCollection;
    }

    /**
     * @param string $storeName
     *
     * @return int
     */
    protected function getIdStoreByName($storeName)
    {
        if (!$this->idStoreMapBuffer) {
            $this->loadIdStoreMap();
        }

        return $this->idStoreMapBuffer[$storeName];
    }

    /**
     * @return void
     */
    protected function loadIdStoreMap()
    {
        $storeTransfers = $this->getFactory()->getStoreFacade()->getAllStores();

        $this->idStoreMapBuffer = [];
        foreach ($storeTransfers as $storeTransfer) {
            $this->idStoreMapBuffer[$storeTransfer->getName()] = $storeTransfer->getIdStore();
        }
    }
}
