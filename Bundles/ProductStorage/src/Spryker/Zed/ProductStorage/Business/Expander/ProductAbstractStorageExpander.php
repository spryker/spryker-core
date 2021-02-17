<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Expander;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface;

class ProductAbstractStorageExpander implements ProductAbstractStorageExpanderInterface
{
    /**
     * @var array|null
     */
    protected static $superAttributesCache;

    /**
     * @var \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface
     */
    protected $productStorageRepository;

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface $productFacade
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface $productStorageRepository
     */
    public function __construct(
        ProductStorageToProductInterface $productFacade,
        ProductStorageRepositoryInterface $productStorageRepository
    ) {
        $this->productFacade = $productFacade;
        $this->productStorageRepository = $productStorageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expandWithAttributeVariantCollection(
        ProductAbstractStorageTransfer $productAbstractStorageTransfer
    ): ProductAbstractStorageTransfer {
        $attributeMapStorageTransfer = $productAbstractStorageTransfer->getAttributeMap();

        if (!$attributeMapStorageTransfer || !$attributeMapStorageTransfer->getProductConcreteIds()) {
            return $productAbstractStorageTransfer;
        }

        $productAttributes = $this->productStorageRepository
            ->getMappedProductAttributes($attributeMapStorageTransfer->getProductConcreteIds());

        $attributeVariantCollection = $this->generateAttributeVariantCollection($productAttributes);
        $productAbstractStorageTransfer->getAttributeMap()->setAttributeVariantCollection($attributeVariantCollection);

        return $productAbstractStorageTransfer;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function generateAttributeVariantCollection(array $productAttributes): array
    {
        $productAttributes = $this->decodeProductAttributes($productAttributes);
        $superAttributes = $this->getSuperAttributes($productAttributes);

        if (count($superAttributes) < 1) {
            return [];
        }

        return $this->mapAttributeVariantCollection($productAttributes, $superAttributes);
    }

    /**
     * @param array $productAttributes
     * @param array $superAttributes
     *
     * @return array
     */
    protected function mapAttributeVariantCollection(array $productAttributes, array $superAttributes): array
    {
        $attributeVariantCollection = [];

        foreach ($productAttributes as $idProductConcrete => $productAttribute) {
            foreach ($productAttribute as $key => $value) {
                if (!isset($superAttributes[$key])) {
                    continue;
                }

                $attributeVariantCollection[$idProductConcrete][$key] = $value;
            }
        }

        return $attributeVariantCollection;
    }

    /**
     * @param array $productAttributes
     *
     * @return array
     */
    protected function decodeProductAttributes(array $productAttributes): array
    {
        $decodedProductAttributes = [];

        foreach ($productAttributes as $idProductConcrete => $productAttribute) {
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
        $uniqueAttributeKeys = $this->filterUniqueAttributeKeys($productAttributes);

        if (static::$superAttributesCache === null) {
            $superAttributeList = $this->productStorageRepository->getProductAttributeKeys();

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
}
