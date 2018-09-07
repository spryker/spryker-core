<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCategoryTreesTransfer;

interface CategoriesResourceMapperInterface
{
    /**
     * @param array $categoriesResource
     *
     * @return \Generated\Shared\Transfer\RestCategoryTreesTransfer
     */
    public function mapCategoriesResourceToRestCategoriesTransfer(array $categoriesResource): RestCategoryTreesTransfer;
}
