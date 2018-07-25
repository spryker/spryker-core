<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
