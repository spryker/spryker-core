<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnitStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer;
use Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer;
use Spryker\Client\ProductPackagingUnitStorage\Dependency\Client\ProductPackagingUnitStorageToStorageClientInterface;
use Spryker\Shared\ProductPackagingUnitStorage\ProductPackagingUnitStorageConstants;

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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer|null
     */
    public function findProductAbstractPackagingById(int $idProductAbstract): ?ProductAbstractPackagingStorageTransfer
    {
        $key = $this->productPackagingUnitStorageKeyGenerator->generateKey(
            ProductPackagingUnitStorageConstants::PRODUCT_PACKAGING_UNIT_RESOURCE_NAME,
            $idProductAbstract
        );

        return $this->findProductAbstractPackagingStorageTransfer($key);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcretePackagingStorageTransfer|null
     */
    public function findProductConcretePackagingById(int $idProductAbstract, int $idProduct): ?ProductConcretePackagingStorageTransfer
    {
        $productAbstractPackagingStorageTransfer = $this->findProductAbstractPackagingById($idProductAbstract);

        if (!$productAbstractPackagingStorageTransfer) {
            return null;
        }

        foreach ($productAbstractPackagingStorageTransfer->getTypes() as $concretePackagingStorageTransfer) {
            if ($concretePackagingStorageTransfer->getIdProduct() === $idProduct) {
                return $concretePackagingStorageTransfer;
            }
        }

        return null;
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAbstractPackagingStorageTransfer|null
     */
    protected function findProductAbstractPackagingStorageTransfer(string $key): ?ProductAbstractPackagingStorageTransfer
    {
        $data = $this->storageClient->get($key);

        if (!$data) {
            return null;
        }

        $productAbstractPackagingStorageTransfer = new ProductAbstractPackagingStorageTransfer();
        $productAbstractPackagingStorageTransfer->fromArray($data, true);

        return $productAbstractPackagingStorageTransfer;
    }
}
