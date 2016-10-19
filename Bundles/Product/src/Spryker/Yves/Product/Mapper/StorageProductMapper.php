<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Product\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

class StorageProductMapper implements StorageProductMapperInterface
{

    /**
     * @var \Spryker\Yves\Product\Mapper\AttributeVariantMapperInterface
     */
    protected $attributeVariantMapper;

    /**
     * @param \Spryker\Yves\Product\Mapper\AttributeVariantMapperInterface $attributeVariantMapper
     */
    public function __construct(AttributeVariantMapperInterface $attributeVariantMapper)
    {
        $this->attributeVariantMapper = $attributeVariantMapper;
    }

    /**
     * @param array $productData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productData, array $selectedAttributes = [])
    {
        $storageProductTransfer = $this->mapAbstractStorageProduct($productData);
        $storageProductTransfer->setSelectedAttributes($selectedAttributes);

        $storageProductTransfer = $this->attributeVariantMapper->setSuperAttributes($storageProductTransfer);
        if (count($selectedAttributes) > 0) {
            $storageProductTransfer = $this->attributeVariantMapper->setSelectedVariants(
                $selectedAttributes,
                $storageProductTransfer
            );
        }

        return $storageProductTransfer;
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapAbstractStorageProduct(array $productData)
    {
        $storageProductTransfer = new StorageProductTransfer();
        $storageProductTransfer->fromArray($productData, true);

        return $storageProductTransfer;
    }

}
