<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Product\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;

class StorageProductMapper implements StorageProductMapperInterface
{
    /**
     * @var \Spryker\Client\Product\Mapper\AttributeVariantMapperInterface
     */
    protected $attributeVariantMapper;

    /**
     * @var \Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected $storageProductExpanderPlugins;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param \Spryker\Client\Product\Mapper\AttributeVariantMapperInterface $attributeVariantMapper
     * @param \Spryker\Client\Product\Dependency\Plugin\StorageProductExpanderPluginInterface[] $storageProductExpanderPlugins
     * @param string $locale
     */
    public function __construct(AttributeVariantMapperInterface $attributeVariantMapper, array $storageProductExpanderPlugins, $locale)
    {
        $this->attributeVariantMapper = $attributeVariantMapper;
        $this->storageProductExpanderPlugins = $storageProductExpanderPlugins;
        $this->locale = $locale;
    }

    /**
     * @param array $productData
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productData, array $selectedAttributes = [])
    {
        $storageProductTransfer = $this->createStorageProductTransfer($productData);
        $storageProductTransfer->setSelectedAttributes($selectedAttributes);

        $storageProductTransfer = $this->attributeVariantMapper->setSuperAttributes($storageProductTransfer);
        if (count($selectedAttributes) > 0) {
            $storageProductTransfer = $this->attributeVariantMapper->setSelectedVariants(
                $selectedAttributes,
                $storageProductTransfer
            );
        }

        foreach ($this->storageProductExpanderPlugins as $storageProductExpanderPlugin) {
            $storageProductTransfer = $storageProductExpanderPlugin->expandStorageProduct($storageProductTransfer, $productData, $this->locale);
        }

        return $storageProductTransfer;
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function createStorageProductTransfer(array $productData)
    {
        $storageProductTransfer = new StorageProductTransfer();
        $storageProductTransfer->fromArray($productData, true);

        return $storageProductTransfer;
    }
}
