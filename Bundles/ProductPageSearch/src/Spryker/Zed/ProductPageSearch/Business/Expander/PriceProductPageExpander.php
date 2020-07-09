<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Expander;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;

class PriceProductPageExpander implements PriceProductPageExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     */
    public function __construct(ProductPageSearchToPriceProductInterface $priceProductFacade, ProductPageSearchToStoreFacadeInterface $storeFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageLoadTransferWithPricesData(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer {
        $productAbstractIds = $productPageLoadTransfer->getProductAbstractIds();

        $pricesByIdProductAbstract = $this->findPricesByIdProductAbstractIn($productAbstractIds);

        $productPageLoadTransfer->setPayloadTransfers(
            $this->updatePayloadTransfers($productPageLoadTransfer->getPayloadTransfers(), $pricesByIdProductAbstract)
        );

        return $productPageLoadTransfer;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[][][]
     */
    protected function findPricesByIdProductAbstractIn(array $productAbstractIds): array
    {
        $productPrices = $this->priceProductFacade
            ->findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria(
                $productAbstractIds,
                $this->getPriceCriteriaTransferForDefaultPriceDimension()
            );
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
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer[] $productPageLoadTransfers
     * @param array $pricesByStoreList
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer[] updated payload transfers
     */
    protected function updatePayloadTransfers(array $productPageLoadTransfers, array $pricesByStoreList): array
    {
        foreach ($productPageLoadTransfers as $productPageLoadTransfer) {
            $pricesByStore = $pricesByStoreList[$productPageLoadTransfer->getIdProductAbstract()] ?? [];
            $productPageLoadTransfer->setPrices($pricesByStore);
        }

        return $productPageLoadTransfers;
    }

    /**
     * @return string[]
     */
    protected function getStoreNameByIdMap(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $idStoreMap = [];
        foreach ($storeTransfers as $storeTransfer) {
            $idStoreMap[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $idStoreMap;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    protected function getPriceCriteriaTransferForDefaultPriceDimension(): PriceProductCriteriaTransfer
    {
        return (new PriceProductCriteriaTransfer())
            ->setPriceDimension(
                (new PriceProductDimensionTransfer())
                    ->setType(ProductPageSearchConfig::PRICE_DIMENSION_DEFAULT)
            );
    }
}
