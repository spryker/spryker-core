<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup\Storage;

use Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToProductInterface;

class ProductStorageReader implements ProductStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductGroup\Storage\ProductAbstractGroupStorageReaderInterface
     */
    protected $productAbstractGroupStorageReader;

    /**
     * @var \Spryker\Client\ProductGroup\Storage\ProductGroupStorageReaderInterface
     */
    protected $productGroupStorageReader;

    /**
     * @var \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToProductInterface
     */
    protected $productClient;

    /**
     * @param \Spryker\Client\ProductGroup\Storage\ProductAbstractGroupStorageReaderInterface $productAbstractGroupStorageReader
     * @param \Spryker\Client\ProductGroup\Storage\ProductGroupStorageReaderInterface $productGroupStorageReader
     * @param \Spryker\Client\ProductGroup\Dependency\Client\ProductGroupToProductInterface $productClient
     */
    public function __construct(
        ProductAbstractGroupStorageReaderInterface $productAbstractGroupStorageReader,
        ProductGroupStorageReaderInterface $productGroupStorageReader,
        ProductGroupToProductInterface $productClient
    ) {
        $this->productAbstractGroupStorageReader = $productAbstractGroupStorageReader;
        $this->productGroupStorageReader = $productGroupStorageReader;
        $this->productClient = $productClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName)
    {
        $productAbstractGroupsTransfer = $this->productAbstractGroupStorageReader->findProductAbstractGroup($idProductAbstract, $localeName);

        if (!$productAbstractGroupsTransfer) {
            return [];
        }

        $productGroupTransfers = $this->productGroupStorageReader->findProductGroups($productAbstractGroupsTransfer, $localeName);

        $idProductAbstracts = $this->getUniqueIdProductAbstracts($productGroupTransfers);
        $idProductAbstracts = $this->moveSubjectProductToFirstPosition($idProductAbstract, $idProductAbstracts);

        return $this->getProductsFromStorage($idProductAbstracts, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer[] $productGroupTransfers
     *
     * @return array
     */
    protected function getUniqueIdProductAbstracts(array $productGroupTransfers)
    {
        $idProductAbstracts = [];

        foreach ($productGroupTransfers as $productGroupTransfer) {
            $idProductAbstracts = array_merge($idProductAbstracts, $productGroupTransfer->getIdProductAbstracts());
        }

        return array_unique($idProductAbstracts);
    }

    /**
     * @param int $idProductAbstract
     * @param array $idProductAbstracts
     *
     * @return array
     */
    protected function moveSubjectProductToFirstPosition($idProductAbstract, array $idProductAbstracts)
    {
        $currentProductIndex = array_search($idProductAbstract, $idProductAbstracts);

        if ($currentProductIndex !== false) {
            unset($idProductAbstracts[$currentProductIndex]);
            array_unshift($idProductAbstracts, $idProductAbstract);
        }

        return $idProductAbstracts;
    }

    /**
     * @param array $idProductAbstracts
     * @param string $localeName
     *
     * @return array
     */
    protected function getProductsFromStorage(array $idProductAbstracts, $localeName)
    {
        $products = [];

        foreach ($idProductAbstracts as $idProductAbstract) {
            $product = $this->productClient->getProductAbstractFromStorageById($idProductAbstract, $localeName);

            if ($product) {
                $products[] = $product;
            }
        }

        return $products;
    }
}
