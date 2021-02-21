<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Generator;

use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface;

class AttributeVariantMapGenerator implements AttributeVariantMapGeneratorInterface
{
    /**
     * @var array|null
     */
    protected static $superAttributesCache;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface
     */
    protected $productStorageRepository;

    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface $productStorageRepository
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     */
    public function __construct(
        ProductStorageRepositoryInterface $productStorageRepository,
        ProductStorageToProductInterface $productFacade
    ) {
        $this->productStorageRepository = $productStorageRepository;
        $this->productFacade = $productFacade;
    }

    /**
     * @param array $productAttributeMapByIdProduct
     *
     * @return array
     */
    public function generateAttributeVariantMap(array $productAttributeMapByIdProduct): array
    {
        $productAttributeMapByIdProduct = $this->decodeProductAttributes($productAttributeMapByIdProduct);
        $superAttributes = $this->getSuperAttributes($productAttributeMapByIdProduct);

        if (count($superAttributes) < 1) {
            return [];
        }

        return $this->mapAttributeVariantMap($productAttributeMapByIdProduct, $superAttributes);
    }

    /**
     * @param array $productAttributeMapByIdProduct
     *
     * @return array
     */
    protected function decodeProductAttributes(array $productAttributeMapByIdProduct): array
    {
        $decodedProductAttributes = [];

        foreach ($productAttributeMapByIdProduct as $idProductConcrete => $productAttribute) {
            $decodedProductAttributes[$idProductConcrete] = $this->productFacade
                ->decodeProductAttributes($productAttribute);
        }

        return $decodedProductAttributes;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function getSuperAttributes(array $productAttributes): array
    {
        $uniqueAttributeKeys = $this->filterUniqueProductAttributeKeys($productAttributes);

        if (static::$superAttributesCache === null) {
            $superAttributeList = $this->productStorageRepository->getProductAttributeKeys();

            static::$superAttributesCache = array_flip($superAttributeList);
        }

        $superAttributes = $this->filterSuperAttributes($uniqueAttributeKeys);
        $superAttributes = array_flip($superAttributes);

        return $superAttributes;
    }

    /**
     * @param array $productAttributeMapByIdProduct
     * @param array $superAttributes
     *
     * @return array
     */
    protected function mapAttributeVariantMap(array $productAttributeMapByIdProduct, array $superAttributes): array
    {
        $attributeVariantMap = [];

        foreach ($productAttributeMapByIdProduct as $idProductConcrete => $productAttributes) {
            $attributeVariantMap = $this->mapAttributeValues(
                $attributeVariantMap,
                $superAttributes,
                $idProductConcrete,
                $productAttributes
            );
        }

        return $attributeVariantMap;
    }

    /**
     * @param array $attributeVariantMap
     * @param array $superAttributes
     * @param int $idProductConcrete
     * @param array $productAttributes
     *
     * @return array
     */
    protected function mapAttributeValues(
        array $attributeVariantMap,
        array $superAttributes,
        int $idProductConcrete,
        array $productAttributes
    ): array {
        foreach ($productAttributes as $key => $value) {
            if (!isset($superAttributes[$key])) {
                continue;
            }

            $attributeVariantMap[$idProductConcrete][$key] = $value;
        }

        return $attributeVariantMap;
    }

    /**
     * @param array $productAttributes
     *
     * @return string[]
     */
    protected function filterUniqueProductAttributeKeys(array $productAttributes): array
    {
        $uniqueAttributes = [];

        foreach ($productAttributes as $attributes) {
            $uniqueAttributes = $this->filterUniqueAttributeKeys($uniqueAttributes, $attributes);
        }

        return array_keys($uniqueAttributes);
    }

    /**
     * @param array $uniqueAttributes
     * @param array $attributes
     *
     * @return array
     */
    protected function filterUniqueAttributeKeys(array $uniqueAttributes, array $attributes): array
    {
        foreach (array_keys($attributes) as $key) {
            if (isset($uniqueAttributes[$key])) {
                continue;
            }

            $uniqueAttributes[$key] = true;
        }

        return $uniqueAttributes;
    }

    /**
     * @param string[] $productAttributeKeys
     *
     * @return array
     */
    protected function filterSuperAttributes(array $productAttributeKeys): array
    {
        return array_filter($productAttributeKeys, function (string $productAttributeKey) {
            return isset(static::$superAttributesCache[$productAttributeKey]);
        });
    }
}
