<?php
/**
 * Created by PhpStorm.
 * User: poidenko
 * Date: 7/24/18
 * Time: 9:00 AM
 */

namespace Spryker\Glue\CategoriesRestApi\Dependency\Client;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

interface CategoriesRestApiToCategoryStorageClientInterface
{
    /**
     * Specification:
     *  - Return category node storage data by locale name.
     *
     * @api
     *
     * @param string $locale
     *
     * @return array
     */
    public function getCategories(string $locale);

    /**
     * Specification:
     *  - Return category node storage data by id category node and locale name.
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById(int $idCategoryNode, string $localeName): CategoryNodeStorageTransfer;
}
