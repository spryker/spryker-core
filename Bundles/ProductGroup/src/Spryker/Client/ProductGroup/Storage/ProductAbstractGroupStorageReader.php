<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup\Storage;

use Generated\Shared\Transfer\ProductAbstractGroupsTransfer;
use Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductAbstractGroupStorageReader implements ProductAbstractGroupStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $productAbstractGroupsKeyBuilder;

    /**
     * @param \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToStorageInterface $storageClient
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $productAbstractGroupsKeyBuilder
     */
    public function __construct(ProductGroupToStorageInterface $storageClient, KeyBuilderInterface $productAbstractGroupsKeyBuilder)
    {
        $this->storageClient = $storageClient;
        $this->productAbstractGroupsKeyBuilder = $productAbstractGroupsKeyBuilder;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupsTransfer|null
     */
    public function findProductAbstractGroup($idProductAbstract, $localeName)
    {
        $key = $this->productAbstractGroupsKeyBuilder->generateKey($idProductAbstract, $localeName);
        $data = $this->storageClient->get($key);

        if (!$data) {
            return null;
        }

        return $this->mapProductAbstractGroupsTransfer($data);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductAbstractGroupsTransfer
     */
    protected function mapProductAbstractGroupsTransfer(array $data)
    {
        $productAbstractGroupsTransfer = new ProductAbstractGroupsTransfer();
        $productAbstractGroupsTransfer->fromArray($data, true);

        return $productAbstractGroupsTransfer;
    }
}
