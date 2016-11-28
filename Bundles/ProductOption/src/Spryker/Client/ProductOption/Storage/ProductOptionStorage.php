<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption\Storage;

use Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer;
use Generated\Shared\Transfer\StorageProductOptionGroupTransfer;
use Spryker\Client\ProductOption\Dependency\Client\ProductOptionToStorageInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class ProductOptionStorage implements ProductOptionStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @param \Spryker\Client\ProductOption\Dependency\Client\ProductOptionToStorageInterface $storage
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct(ProductOptionToStorageInterface $storage, KeyBuilderInterface $keyBuilder, $localeName)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->localeName = $localeName;
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    public function get($idAbstractProduct)
    {
        $productOptionKey = $this->keyBuilder->generateKey($idAbstractProduct, $this->localeName);

        $productOptions = $this->storage->get($productOptionKey);
        if (!$productOptions || !is_array($productOptions)) {
            return new StorageProductOptionGroupCollectionTransfer();
        }

        return $this->mapProductOptionGroups($productOptions);
    }

    /**
     * @param array $productOptions
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    protected function mapProductOptionGroups(array $productOptions)
    {
        $productOptionGroupsTransfer = new StorageProductOptionGroupCollectionTransfer();
        foreach ($productOptions as $productOption) {
            if (!$productOption) {
                continue;
            }

            $storageProductOptionGroupTransfer = new StorageProductOptionGroupTransfer();
            $storageProductOptionGroupTransfer->fromArray($productOption, true);

            $productOptionGroupsTransfer->addProductOptionGroup($storageProductOptionGroupTransfer);
        }

        return $productOptionGroupsTransfer;
    }

}
