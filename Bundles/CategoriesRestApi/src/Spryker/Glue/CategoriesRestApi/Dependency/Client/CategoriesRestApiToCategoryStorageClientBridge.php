<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Dependency\Client;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

class CategoriesRestApiToCategoryStorageClientBridge implements CategoriesRestApiToCategoryStorageClientInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    protected $categoryStorageClient;

    /**
     * @param \Spryker\Client\CategoryStorage\CategoryStorageClientInterface $categoryStorageClient
     */
    public function __construct($categoryStorageClient)
    {
        $this->categoryStorageClient = $categoryStorageClient;
    }

    /**
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories(string $localeName, string $storeName): ArrayObject
    {
        return $this->categoryStorageClient->getCategories($localeName, $storeName);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName, string $storeName): CategoryNodeStorageTransfer
    {
        return $this->categoryStorageClient->getCategoryNodeById($idCategoryNode, $localeName, $storeName);
    }

    /**
     * @param int[] $categoryNodeIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function getCategoryNodeByIds(array $categoryNodeIds, string $localeName, string $storeName): array
    {
        return $this->categoryStorageClient->getCategoryNodeByIds($categoryNodeIds, $localeName, $storeName);
    }
}
