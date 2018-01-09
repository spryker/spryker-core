<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig;

class ProductAbstractImageStorageReader implements ProductAbstractImageStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGeneratorInterface
     */
    protected $productImageStorageKeyGenerator;

    /**
     * @param \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGeneratorInterface $productImageStorageKeyGenerator
     */
    public function __construct(ProductImageStorageToStorageInterface $storageClient, ProductImageStorageKeyGeneratorInterface $productImageStorageKeyGenerator)
    {
        $this->storageClient = $storageClient;
        $this->productImageStorageKeyGenerator = $productImageStorageKeyGenerator;
    }

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer($idProductAbstract, $locale)
    {
        $key = $this->productImageStorageKeyGenerator->generateKey(ProductImageStorageConfig::PRODUCT_ABSTRACT_IMAGE_RESOURCE_NAME, $idProductAbstract, $locale);

        return $this->findProductImageProductStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    protected function findProductImageProductStorageTransfer($key)
    {
        $imageData = $this->storageClient->get($key);

        if (!$imageData) {
            return null;
        }

        $productImageStorageTransfer = new ProductAbstractImageStorageTransfer();
        $productImageStorageTransfer->fromArray($imageData, true);

        return $productImageStorageTransfer;
    }
}
