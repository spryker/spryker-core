<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption\Storage;

use Generated\Shared\Transfer\ProductOptionGroupsTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Spryker\Client\Storage\StorageClientInterface;
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
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct(StorageClientInterface $storage, KeyBuilderInterface $keyBuilder, $localeName)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->localeName = $localeName;
    }

    /**
     * @param int $idAbstractProduct
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupsTransfer
     */
    public function get($idAbstractProduct)
    {
        $productOptionKey = $this->keyBuilder->generateKey($idAbstractProduct, $this->localeName);

        $productOptions = $this->storage->get($productOptionKey);
        if (!$productOptions || !is_array($productOptions)) {
            return new ProductOptionGroupsTransfer();
        }

        return $this->hydrateProductOptionGroups($productOptions);
    }

    /**
     * @param array $productOptions
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupsTransfer
     */
    protected function hydrateProductOptionGroups(array $productOptions)
    {
        $productOptionGroupsTransfer = new ProductOptionGroupsTransfer();
        foreach ($productOptions as $productOption) {
            $productOptionGroupTransfer = new ProductOptionGroupTransfer();
            $productOptionGroupTransfer->fromArray($productOption, true);

            $this->hydrateProductOptionGroupValue($productOption['values'], $productOptionGroupTransfer);

            $productOptionGroupsTransfer->addProductOptionGroup($productOptionGroupTransfer);
        }

        return $productOptionGroupsTransfer;
    }

    /**
     * @param array $values
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function hydrateProductOptionGroupValue(
        array $values,
        ProductOptionGroupTransfer $productOptionGroupTransfer
    ) {
        foreach ($values as $value) {
            $productOptionValueTransfer = new ProductOptionValueTransfer();
            $productOptionValueTransfer->fromArray($value, true);
            $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);
        }
    }

}
