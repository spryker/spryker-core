<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupsTransfer;
use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductGroupStorageReader implements ProductGroupStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $productGroupKeyBuilder;

    /**
     * @param \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface $storageClient
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $productGroupKeyBuilder
     */
    public function __construct(ProductGroupToStorageInterface $storageClient, KeyBuilderInterface $productGroupKeyBuilder)
    {
        $this->storageClient = $storageClient;
        $this->productGroupKeyBuilder = $productGroupKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractGroupsTransfer $productAbstractGroupsTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer[]
     */
    public function findProductGroups(ProductAbstractGroupsTransfer $productAbstractGroupsTransfer, $localeName)
    {
        $productGroupTransfers = [];

        foreach ($productAbstractGroupsTransfer->getIdProductGroups() as $idProductGroup) {
            $key = $this->productGroupKeyBuilder->generateKey($idProductGroup, $localeName);
            $data = $this->storageClient->get($key);

            if (!$data) {
                continue;
            }

            $productGroupTransfers[] = $this->mapProductGroupTransfer($data);
        }

        return $productGroupTransfers;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    protected function mapProductGroupTransfer(array $data)
    {
        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->fromArray($data, true);

        return $productGroupTransfer;
    }
}
