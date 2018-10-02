<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\StorageProductAbstractRelationTransfer;
use Generated\Shared\Transfer\StorageProductImageTransfer;
use Generated\Shared\Transfer\StorageProductRelationsTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductRelationCollector\Dependency\Facade\ProductRelationCollectorToPriceProductFacadeInterface;
use Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductImageInterface;
use Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductRelationInterface;
use Spryker\Zed\ProductRelationCollector\Persistence\Collector\Propel\ProductRelationCollectorQuery;

class ProductRelationCollector extends AbstractStoragePropelCollector
{
    /**
     * @var \Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductImageInterface
     */
    protected $productImageQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelationCollector\Dependency\Facade\ProductRelationCollectorToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductRelationInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductImageInterface $productImageQueryContainer
     * @param \Spryker\Zed\ProductRelationCollector\Dependency\Facade\ProductRelationCollectorToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductRelationInterface $productRelationQueryContainer
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        ProductRelationCollectorToProductImageInterface $productImageQueryContainer,
        ProductRelationCollectorToPriceProductFacadeInterface $priceProductFacade,
        ProductRelationCollectorToProductRelationInterface $productRelationQueryContainer
    ) {
        $this->productImageQueryContainer = $productImageQueryContainer;
        $this->priceProductFacade = $priceProductFacade;
        $this->productRelationQueryContainer = $productRelationQueryContainer;

        parent::__construct($utilDataReaderService);
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $relatedProductIds = explode(',', $collectItemData[ProductRelationCollectorQuery::PRODUCT_RELATIONS]);

        $results = [];
        foreach ($relatedProductIds as $idProductRelation) {
            $relationProducts = $this->findRelationProducts((int)$idProductRelation);

            foreach ($relationProducts as $relationProduct) {
                if (!isset($results[$relationProduct[SpyProductRelationTypeTableMap::COL_KEY]])) {
                    $results[$relationProduct[SpyProductRelationTypeTableMap::COL_KEY]] = [
                        StorageProductRelationsTransfer::ABSTRACT_PRODUCTS => [],
                        StorageProductRelationsTransfer::IS_ACTIVE => $collectItemData[ProductRelationCollectorQuery::IS_ACTIVE],
                    ];
                }
                $results[$relationProduct[SpyProductRelationTypeTableMap::COL_KEY]][StorageProductRelationsTransfer::ABSTRACT_PRODUCTS][] = $this->mapProductRelation($relationProduct);
            }
        }

        return $results;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

    /**
     * @param array $relationProduct
     *
     * @return array
     */
    protected function mapProductRelation(array $relationProduct)
    {
        return [
            StorageProductAbstractRelationTransfer::ID_PRODUCT_ABSTRACT => $relationProduct[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT],
            StorageProductAbstractRelationTransfer::NAME => $relationProduct[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME],
            StorageProductAbstractRelationTransfer::PRICES => $this->findPricesBySku($relationProduct[SpyProductAbstractTableMap::COL_SKU]),
            StorageProductAbstractRelationTransfer::SKU => $relationProduct[SpyProductAbstractTableMap::COL_SKU],
            StorageProductAbstractRelationTransfer::URL => $relationProduct[SpyUrlTableMap::COL_URL],
            StorageProductAbstractRelationTransfer::ORDER => $relationProduct[SpyProductRelationProductAbstractTableMap::COL_ORDER],
            StorageProductAbstractRelationTransfer::IMAGE_SETS => $this->generateProductAbstractImageSets(
                $relationProduct[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]
            ),
        ];
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|mixed
     */
    protected function findRelationProducts($idProductRelation)
    {
        return $this->productRelationQueryContainer
            ->queryProductRelationWithProductAbstractByIdRelationAndLocale(
                $idProductRelation,
                $this->locale->getIdLocale()
            )
            ->find();
    }

    /**
     * @param string $sku
     *
     * @return array
     */
    protected function findPricesBySku($sku)
    {
        return $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore($sku);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function generateProductAbstractImageSets($idProductAbstract)
    {
        $imageSets = $this->productImageQueryContainer
            ->queryImageSetByProductAbstractId($idProductAbstract)
            ->find();

        $result = [];
        foreach ($imageSets as $imageSetEntity) {
            $result[$imageSetEntity->getName()] = [];
            foreach ($imageSetEntity->getSpyProductImageSetToProductImages() as $productsToImageEntity) {
                $imageEntity = $productsToImageEntity->getSpyProductImage();
                $result[$imageSetEntity->getName()][] = [
                    StorageProductImageTransfer::ID_PRODUCT_IMAGE => $imageEntity->getIdProductImage(),
                    StorageProductImageTransfer::EXTERNAL_URL_LARGE => $imageEntity->getExternalUrlLarge(),
                    StorageProductImageTransfer::EXTERNAL_URL_SMALL => $imageEntity->getExternalUrlSmall(),
                ];
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION;
    }
}
