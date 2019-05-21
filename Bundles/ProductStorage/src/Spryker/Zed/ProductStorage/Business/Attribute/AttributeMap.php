<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Attribute;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Exception\InvalidArgumentException;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;

class AttributeMap implements AttributeMapInterface
{
    public const KEY_ID_PRODUCT_ABSTRACT_FK_LOCALE = '%d_%d';

    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array|null
     */
    protected static $superAttributesCache = null;

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

        return $this->generateAttributeMapByConcreteProducts($concreteProducts);
    }

    /**
     * @param array $productAbstractIds
     * @param array $localeIds
     *
     * @throws \Spryker\Zed\ProductStorage\Exception\InvalidArgumentException
     *
     * @return array
     */
    public function generateAttributeMapBulk(array $productAbstractIds, array $localeIds): array
    {
        if (count($productAbstractIds) !== count($localeIds)) {
            throw new InvalidArgumentException('Arrays should be paired.');
        }

        $concreteProducts = $this->getConcreteProductsBulk($productAbstractIds, $localeIds);
        $indexedConcreteProducts = $this->getIndexedConcreteProducts($concreteProducts);

        $attributeMapBulk = [];
        foreach ($indexedConcreteProducts as $key => $concreteProducts) {
            $attributeMapBulk[$key] = $this->generateAttributeMapByConcreteProducts($concreteProducts);
        }

        return $attributeMapBulk;
    }

    /**
     * @param array $concreteProducts
     *
     * @return \Generated\Shared\Transfer\AttributeMapStorageTransfer
     */
    protected function generateAttributeMapByConcreteProducts(array $concreteProducts): AttributeMapStorageTransfer
    {
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
     * @param array $concreteProducts
     *
     * @return array
     */
    protected function getIndexedConcreteProducts(array $concreteProducts): array
    {
        $indexedConcreteProducts = [];
        foreach ($concreteProducts as $concreteProduct) {
            $idProductAbstract = $concreteProduct[SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT];
            $idLocale = $concreteProduct['fk_locale'];
            $key = $this->getProductAbstractLocaleKey($idProductAbstract, $idLocale);

            $indexedConcreteProducts[$key][] = $concreteProduct;
        }

        return $indexedConcreteProducts;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param array $attributeMapBulk
     *
     * @return \Generated\Shared\Transfer\AttributeMapStorageTransfer
     */
    public function getConcreteProductsFromBulk(int $idProductAbstract, int $idLocale, array $attributeMapBulk): AttributeMapStorageTransfer
    {
        $key = $this->getProductAbstractLocaleKey($idProductAbstract, $idLocale);

        if (!isset($attributeMapBulk[$key])) {
            return new AttributeMapStorageTransfer();
        }

        return $attributeMapBulk[$key];
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return string
     */
    protected function getProductAbstractLocaleKey(int $idProductAbstract, int $idLocale): string
    {
        return sprintf(static::KEY_ID_PRODUCT_ABSTRACT_FK_LOCALE, $idProductAbstract, $idLocale);
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
     * @return \Orm\Zed\Product\Persistence\SpyProduct[]
     */
    protected function getConcreteProducts($idProductAbstract, $idLocale)
    {
        return $this->queryContainer
            ->queryConcreteProduct($idProductAbstract, $idLocale)
            ->find()
            ->toArray(null, false, TableMap::TYPE_CAMELNAME);
    }

    /**
     * @param int[] $productAbstractIds
     * @param int[] $localeIds
     *
     * @return array
     */
    protected function getConcreteProductsBulk(array $productAbstractIds, array $localeIds): array
    {
        return $this->queryContainer
            ->queryConcreteProductBulk($productAbstractIds, $localeIds)
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

        if (static::$superAttributesCache === null) {
            $superAttributeList = $this->queryContainer
                ->queryProductAttributeKey()
                ->select(SpyProductAttributeKeyTableMap::COL_KEY)
                ->find()
                ->toArray();

            static::$superAttributesCache = array_flip($superAttributeList);
        }

        $superAttributes = $this->filterSuperAttributes(array_keys($uniqueAttributeKeys));

        $superAttributes = array_flip($superAttributes);

        return $superAttributes;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function filterSuperAttributes(array $productAttributes): array
    {
        return array_filter($productAttributes, function (string $attribute) {
            return isset(static::$superAttributesCache[$attribute]);
        });
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
