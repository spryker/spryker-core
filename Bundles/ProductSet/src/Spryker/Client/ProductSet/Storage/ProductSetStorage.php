<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet\Storage;

use Spryker\Client\ProductSet\Dependency\Client\ProductSetToStorageInterface;
use Spryker\Client\ProductSet\Dependency\Service\ProductSetToUtilEncodingInterface;
use Spryker\Client\ProductSet\Mapper\ProductSetStorageMapperInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class ProductSetStorage implements ProductSetStorageInterface
{

    /**
     * @var \Spryker\Client\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\Product\Dependency\Client\ProductToStorageInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var \Spryker\Client\ProductSet\Mapper\ProductSetStorageMapperInterface
     */
    protected $productSetStorageMapper;

    /**
     * @param \Spryker\Client\ProductSet\Dependency\Client\ProductSetToStorageInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param \Spryker\Client\ProductSet\Dependency\Service\ProductSetToUtilEncodingInterface $utilEncodingService
     * @param string $localeName
     * @param \Spryker\Client\ProductSet\Mapper\ProductSetStorageMapperInterface $productSetStorageMapper
     */
    public function __construct(
        ProductSetToStorageInterface $storage,
        KeyBuilderInterface $keyBuilder,
        ProductSetToUtilEncodingInterface $utilEncodingService,
        $localeName,
        ProductSetStorageMapperInterface $productSetStorageMapper
    ) {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->utilEncodingService = $utilEncodingService;
        $this->localeName = $localeName;
        $this->productSetStorageMapper = $productSetStorageMapper;
    }

    /**
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer|null
     */
    public function findProductSetByIdProductSet($idProductSet)
    {
        $key = $this->keyBuilder->generateKey($idProductSet, $this->localeName);
        $productSet = $this->storage->get($key);

        if (!$productSet) {
            return null;
        }

        return $this->productSetStorageMapper->mapDataToTransfer($productSet);
    }

}
