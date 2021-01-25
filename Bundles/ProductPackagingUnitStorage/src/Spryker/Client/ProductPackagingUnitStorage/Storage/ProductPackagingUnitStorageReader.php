<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Storage;

use Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface;
use Spryker\Shared\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig;

class ProductPackagingUnitStorageReader implements ProductPackagingUnitStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGeneratorInterface
     */
    protected $productPackagingUnitStorageKeyGenerator;

    /**
     * @param \Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductPackagingUnitStorage\Storage\ProductPackagingUnitStorageKeyGeneratorInterface $productPackagingUnitStorageKeyGenerator
     */
    public function __construct(
        ProductPackagingUnitStorageToStorageClientInterface $storageClient,
        ProductPackagingUnitStorageKeyGeneratorInterface $productPackagingUnitStorageKeyGenerator
    ) {
        $this->storageClient = $storageClient;
        $this->productPackagingUnitStorageKeyGenerator = $productPackagingUnitStorageKeyGenerator;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null
     */
    public function findProductPackagingUnitById(int $idProductConcrete): ?ProductPackagingUnitStorageTransfer
    {
        $key = $this->productPackagingUnitStorageKeyGenerator->generateKey(
            ProductPackagingUnitStorageConfig::PRODUCT_PACKAGING_UNIT_RESOURCE_NAME,
            $idProductConcrete
        );

        return $this->findProductPackagingUnitStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitStorageTransfer|null
     */
    protected function findProductPackagingUnitStorageTransfer(string $key): ?ProductPackagingUnitStorageTransfer
    {
        $data = $this->storageClient->get($key);

        if (!$data) {
            return null;
        }

        $productPackagingUnitStorageTransfer = new ProductPackagingUnitStorageTransfer();
        $productPackagingUnitStorageTransfer->fromArray($data, true);

        return $productPackagingUnitStorageTransfer;
    }
}
