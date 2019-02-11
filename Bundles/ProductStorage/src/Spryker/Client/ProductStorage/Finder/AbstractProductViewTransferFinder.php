<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Finder;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface;
use Spryker\Client\ProductStorage\ProductStorageConfig;

abstract class AbstractProductViewTransferFinder implements ProductViewTransferFinderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface
     */
    protected $productStorageDataMapper;

    /**
     * @param \Spryker\Client\ProductStorage\Mapper\ProductStorageDataMapperInterface $productStorageDataMapper
     */
    public function __construct(ProductStorageDataMapperInterface $productStorageDataMapper)
    {
        $this->productStorageDataMapper = $productStorageDataMapper;
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductViewTransfer(int $idProduct, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        $data = $this->findProductStorageData($idProduct, $localeName);
        if ($data === null) {
            return null;
        }

        if (!isset($data[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP])) {
            $data[ProductStorageConfig::RESOURCE_TYPE_ATTRIBUTE_MAP] = [];
        }

        return $this->productStorageDataMapper->mapProductStorageData($localeName, $data, $selectedAttributes);
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return array|null
     */
    abstract protected function findProductStorageData(int $idProduct, string $localeName): ?array;
}
