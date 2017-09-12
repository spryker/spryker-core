<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PyzProduct\Mapper;

use Generated\Shared\Transfer\StorageProductTransfer;
use Symfony\Component\HttpFoundation\Request;

class StorageProductMapper implements StorageProductMapperInterface
{

    /**
     * @var \Spryker\Yves\PyzProduct\Mapper\AttributeVariantMapperInterface
     */
    protected $attributeVariantMapper;

    /**
     * @var \Spryker\Yves\PyzProduct\Dependency\Plugin\StorageProductExpanderPluginInterface[]
     */
    protected $storageProductExpanderPlugins;

    /**
     * @param \Spryker\Yves\PyzProduct\Mapper\AttributeVariantMapperInterface $attributeVariantMapper
     * @param \Spryker\Yves\PyzProduct\Dependency\Plugin\StorageProductExpanderPluginInterface[] $storageProductExpanderPlugins
     */
    public function __construct(AttributeVariantMapperInterface $attributeVariantMapper, array $storageProductExpanderPlugins = [])
    {
        $this->attributeVariantMapper = $attributeVariantMapper;
        $this->storageProductExpanderPlugins = $storageProductExpanderPlugins;
    }

    /**
     * @param array $productData
     * @param Request $request
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    public function mapStorageProduct(array $productData, Request $request, array $selectedAttributes = [])
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

        foreach ($this->storageProductExpanderPlugins as $storageProductExpanderPlugin) {
            $storageProductTransfer = $storageProductExpanderPlugin->expandStorageProduct(
                $storageProductTransfer,
                $productData,
                $request
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
