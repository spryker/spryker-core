<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup\Storage;

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
     * @param array $productAbstractGroups
     * @param string $localeName
     *
     * @return array
     */
    public function getIdProductAbstracts(array $productAbstractGroups, $localeName)
    {
        $idProductAbstracts = [];

        foreach ($productAbstractGroups as $idProductGroup) {
            $key = $this->productGroupKeyBuilder->generateKey($idProductGroup, $localeName);
            $idProductAbstracts = array_merge($idProductAbstracts, (array)$this->storageClient->get($key));
        }
        $idProductAbstracts = array_unique($idProductAbstracts);

        return $idProductAbstracts;
    }

}
