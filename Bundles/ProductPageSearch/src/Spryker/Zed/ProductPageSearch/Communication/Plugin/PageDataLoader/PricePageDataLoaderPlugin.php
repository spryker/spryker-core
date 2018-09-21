<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class PricePageDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        $productAbstractIds = $loadTransfer->getProductAbstractIds();

        $pricesByIdProductAbstract = $this->findPricesByIdProductAbstractIn($productAbstractIds);

        $loadTransfer->setPayloadTransfers(
            $this->updatePayloadTransfers($loadTransfer->getPayloadTransfers(), $pricesByIdProductAbstract)
        );

        return $loadTransfer;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findPricesByIdProductAbstractIn(array $productAbstractIds): array
    {
        $productPrices = $this->getFactory()
            ->getPriceProductFacade()
            ->findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn($productAbstractIds);

        $productPricesMappedById = $this->getProductPricesMappedByIdAndStoreName($productPrices);

        $groupedProductPriceCollection = [];
        foreach ($productPricesMappedById as $idAbstractProduct => $priceProductTransfersByStore) {
            foreach ($priceProductTransfersByStore as $storeName => $priceProductTransfers) {
                $groupedProductPriceCollection[$idAbstractProduct][$storeName] = $priceProductTransfers;
            }
        }

        return $groupedProductPriceCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $productPrices
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[][][]
     */
    protected function getProductPricesMappedByIdAndStoreName(array $productPrices): array
    {
        $storeNameToIdMap = $this->getStoreNameByIdMap();

        $productPricesMappedById = [];
        foreach ($productPrices as $productPrice) {
            $idProductAbstract = $productPrice->getIdProductAbstract();
            $idStore = $productPrice->getMoneyValue()->getFkStore();
            $storeName = $storeNameToIdMap[$idStore];

            $productPricesMappedById[$idProductAbstract][$storeName][] = $productPrice;
        }

        return $productPricesMappedById;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $payloadTransfers
     * @param array $pricesByStoreList
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[] updated payload transfers
     */
    protected function updatePayloadTransfers(array $payloadTransfers, array $pricesByStoreList): array
    {
        foreach ($payloadTransfers as $payloadTransfer) {
            $pricesByStore = $pricesByStoreList[$payloadTransfer->getIdProductAbstract()] ?? [];
            $payloadTransfer->setPrices($pricesByStore);
        }

        return $payloadTransfers;
    }

    /**
     * @return array
     */
    protected function getStoreNameByIdMap(): array
    {
        $storeTransfers = $this->getFactory()->getStoreFacade()->getAllStores();

        $idStoreMap = [];
        foreach ($storeTransfers as $storeTransfer) {
            $idStoreMap[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $idStoreMap;
    }
}
