<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Expander;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Zed\ProductStorage\Business\Generator\AttributeVariantMapGeneratorInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface;

class ProductAbstractStorageExpander implements ProductAbstractStorageExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface
     */
    protected $productStorageRepository;

    /**
     * @var \Spryker\Zed\ProductStorage\Business\Generator\AttributeVariantMapGeneratorInterface
     */
    protected $attributeVariantMapGenerator;

    /**
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface $productStorageRepository
     * @param \Spryker\Zed\ProductStorage\Business\Generator\AttributeVariantMapGeneratorInterface $attributeVariantMapGenerator
     */
    public function __construct(
        ProductStorageRepositoryInterface $productStorageRepository,
        AttributeVariantMapGeneratorInterface $attributeVariantMapGenerator
    ) {
        $this->productStorageRepository = $productStorageRepository;
        $this->attributeVariantMapGenerator = $attributeVariantMapGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expandWithAttributeVariantMap(
        ProductAbstractStorageTransfer $productAbstractStorageTransfer
    ): ProductAbstractStorageTransfer {
        $attributeMapStorageTransfer = $productAbstractStorageTransfer->getAttributeMap();

        if (!$attributeMapStorageTransfer || !$attributeMapStorageTransfer->getProductConcreteIds()) {
            return $productAbstractStorageTransfer;
        }

        $productAttributeMapByIdProduct = $this->productStorageRepository
            ->getMappedProductAttributes($attributeMapStorageTransfer->getProductConcreteIds());

        $attributeVariantMap = $this->attributeVariantMapGenerator->generateAttributeVariantMap($productAttributeMapByIdProduct);
        $productAbstractStorageTransfer->getAttributeMap()->setAttributeVariantMap($attributeVariantMap);

        return $productAbstractStorageTransfer;
    }
}
