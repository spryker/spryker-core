<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Attribute;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;

class AttributeMap implements AttributeMapInterface
{
    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface $queryContainer
     */
    public function __construct(ProductStorageToProductInterface $productFacade, ProductStorageQueryContainerInterface $queryContainer)
    {
        $this->productFacade = $productFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\AttributeMapStorageTransfer
     */
    public function generateAttributeMap($idProductAbstract, $idLocale)
    {
        $concreteProducts = $this->getConcreteProducts($idProductAbstract, $idLocale);
        $concreteProductIds = $this->filterConcreteProductIds($concreteProducts);
        $productAttributes = $this->getProductAttributes($concreteProducts);
        $superAttributes = $this->getSuperAttributes($productAttributes);

        if (count($superAttributes) < 1) {
            return $this->createAttributeMapStorageTransfer($concreteProductIds);
        }

        $productConcreteSuperAttributes = [];
        $superAttributeVariations = [];
        foreach ($productAttributes as $idProductConcrete => $attributes) {
            foreach ($attributes as $key => $value) {
                if (!isset($superAttributes[$key])) {
                    continue;
                }

                $productConcreteSuperAttributes[$idProductConcrete][$key] = $value;
                if (!isset($superAttributeVariations[$key]) || !in_array($value, $superAttributeVariations[$key])) {
                    $superAttributeVariations[$key][] = $value;
                }
            }
        }

        return $this->createAttributeMapStorageTransfer(
            $concreteProductIds,
            $superAttributeVariations,
            $this->buildProductVariants($productConcreteSuperAttributes)
        );
    }

    /**
     * @param array $concreteProductIds
     * @param array $superAttributes
     * @param array $attributeVariants
     *
     * @return \Generated\Shared\Transfer\AttributeMapStorageTransfer
     */
    protected function createAttributeMapStorageTransfer(
        array $concreteProductIds,
        array $superAttributes = [],
        array $attributeVariants = []
    ) {
        return (new AttributeMapStorageTransfer())
            ->setProductConcreteIds($concreteProductIds)
            ->setSuperAttributes($superAttributes)
            ->setAttributeVariants($attributeVariants);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getConcreteProducts($idProductAbstract, $idLocale)
    {
        return $this->queryContainer
            ->queryConcreteProduct($idProductAbstract, $idLocale)
            ->find()
            ->toArray(null, false, TableMap::TYPE_CAMELNAME);
    }

    /**
     * @param array $concreteProducts
     *
     * @return array
     */
    protected function getProductAttributes(array $concreteProducts)
    {
        $productAttributes = [];
        foreach ($concreteProducts as $concreteProduct) {
            $concreteAttributes = $this->productFacade
                ->decodeProductAttributes($concreteProduct[SpyProductTableMap::COL_ATTRIBUTES]);

            $concreteLocalizedAttributes = $this->productFacade
                ->decodeProductAttributes($concreteProduct['localized_attributes']);

            $rawProductAttributesTransfer = new RawProductAttributesTransfer();
            $rawProductAttributesTransfer
                ->setConcreteAttributes($concreteAttributes)
                ->setConcreteLocalizedAttributes($concreteLocalizedAttributes);

            $idConcreteProduct = $concreteProduct[SpyProductTableMap::COL_ID_PRODUCT];
            $productAttributes[$idConcreteProduct] = $this->productFacade->combineRawProductAttributes($rawProductAttributesTransfer);
        }

        return $productAttributes;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function getSuperAttributes(array $productAttributes)
    {
        $uniqueAttributeKeys = $this->filterUniqueAttributeKeys($productAttributes);

        $superAttributes = $this->queryContainer
            ->queryProductAttributeKeyByKey(array_keys($uniqueAttributeKeys))
            ->find()
            ->toArray();

        $superAttributes = array_flip($superAttributes);

        return $superAttributes;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function filterUniqueAttributeKeys(array $productAttributes)
    {
        $uniqueAttributes = [];
        foreach ($productAttributes as $attributes) {
            foreach (array_keys($attributes) as $key) {
                if (isset($uniqueAttributes[$key])) {
                    continue;
                }

                $uniqueAttributes[$key] = true;
            }
        }
        return $uniqueAttributes;
    }

    /**
     * @param array $productSuperAttributes
     *
     * @return array
     */
    protected function buildProductVariants(array $productSuperAttributes)
    {
        $attributeVariants = [];
        if (count($productSuperAttributes) > 1) {
            foreach ($productSuperAttributes as $productId => $attributes) {
                $attributeVariants = array_merge_recursive(
                    $attributeVariants,
                    $this->productFacade->generateAttributePermutations($attributes, $productId)
                );
            }
        }

        return $attributeVariants;
    }

    /**
     * @param array $concreteProducts
     *
     * @return array
     */
    protected function filterConcreteProductIds(array $concreteProducts)
    {
        $concreteProductIds = [];
        foreach ($concreteProducts as $product) {
            $concreteProductIds[$product[SpyProductTableMap::COL_SKU]] = $product[SpyProductTableMap::COL_ID_PRODUCT];
        }

        asort($concreteProductIds);

        return $concreteProductIds;
    }
}
