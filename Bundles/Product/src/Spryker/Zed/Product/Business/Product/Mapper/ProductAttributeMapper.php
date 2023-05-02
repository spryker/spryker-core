<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;

class ProductAttributeMapper implements ProductAttributeMapperInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    /**
     * @param \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface $attributeEncoder
     */
    public function __construct(AttributeEncoderInterface $attributeEncoder)
    {
        $this->attributeEncoder = $attributeEncoder;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    public function mapProductAttributeEntityToProductAbstractTransfer(
        SpyProductAbstractLocalizedAttributes $productAbstractLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer {
        $localizedAttributesData = $productAbstractLocalizedAttributesEntity->toArray();
        if (isset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES])) {
            unset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES]);
        }

        return $localizedAttributesTransfer
            ->fromArray($localizedAttributesData, true)
            ->setAttributes($this->attributeEncoder->decodeAttributes($productAbstractLocalizedAttributesEntity->getAttributes()));
    }
}
