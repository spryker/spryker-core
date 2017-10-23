<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class AttributeKeyManager implements AttributeKeyManagerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAttributeKey($key)
    {
        return $this->productQueryContainer
            ->queryProductAttributeKey()
            ->filterByKey($key)
            ->count() > 0;
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findAttributeKey($key)
    {
        $productAttributeKeyEntity = $this->productQueryContainer
            ->queryProductAttributeKey()
            ->filterByKey($key)
            ->findOne();

        if ($productAttributeKeyEntity === null) {
            return null;
        }

        $productAttributeKeyTransfer = (new ProductAttributeKeyTransfer())
            ->fromArray($productAttributeKeyEntity->toArray(), true);

        return $productAttributeKeyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $attributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function createAttributeKey(ProductAttributeKeyTransfer $attributeKeyTransfer)
    {
        $attributeKeyTransfer->requireKey();

        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->fromArray($attributeKeyTransfer->toArray());

        $productAttributeKeyEntity->save();

        $attributeKeyTransfer->fromArray($productAttributeKeyEntity->toArray(), true);

        return $attributeKeyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $attributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function updateAttributeKey(ProductAttributeKeyTransfer $attributeKeyTransfer)
    {
        $attributeKeyTransfer->requireIdProductAttributeKey();

        $productAttributeKeyEntity = $this->productQueryContainer
            ->queryProductAttributeKey()
            ->filterByIdProductAttributeKey($attributeKeyTransfer->getIdProductAttributeKey())
            ->findOne();

        $productAttributeKeyEntity->fromArray($attributeKeyTransfer->modifiedToArray());

        $productAttributeKeyEntity->save();

        $attributeKeyTransfer->fromArray($productAttributeKeyEntity->toArray(), true);

        return $attributeKeyTransfer;
    }
}
