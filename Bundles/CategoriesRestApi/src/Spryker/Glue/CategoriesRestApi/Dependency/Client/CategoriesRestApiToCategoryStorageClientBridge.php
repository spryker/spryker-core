<?php
/**
 * Created by PhpStorm.
 * User: poidenko
 * Date: 7/24/18
 * Time: 8:59 AM
 */

namespace Spryker\Glue\CategoriesRestApi\Dependency\Client;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

class CategoriesRestApiToCategoryStorageClientBridge implements CategoriesRestApiToCategoryStorageClientInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    protected $categoryStorageClient;

    /**
     * CategoriesRestApiToCategoryStorageClientBridge constructor.
     *
     * @param \Spryker\Client\CategoryStorage\CategoryStorageClientInterface $categoryStorageClient
     */
    public function __construct($categoryStorageClient)
    {
        $this->categoryStorageClient = $categoryStorageClient;
    }

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getCategories(string $locale)
    {
        return $this->categoryStorageClient->getCategories($locale);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName): CategoryNodeStorageTransfer
    {
        return $this->categoryStorageClient->getCategoryNodeById($idCategoryNode, $localeName);
    }
}
