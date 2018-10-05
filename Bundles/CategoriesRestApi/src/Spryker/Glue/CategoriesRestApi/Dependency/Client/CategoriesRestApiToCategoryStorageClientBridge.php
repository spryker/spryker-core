<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Dependency\Client;

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
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]|\ArrayObject
     */
    public function getCategories($locale)
    {
        return $this->categoryStorageClient->getCategories($locale);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName)
    {
        return $this->categoryStorageClient->getCategoryNodeById($idCategoryNode, $localeName);
    }
}
