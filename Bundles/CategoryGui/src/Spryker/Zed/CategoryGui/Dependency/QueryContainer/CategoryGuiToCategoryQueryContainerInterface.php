<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Dependency\QueryContainer;

use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;

interface CategoryGuiToCategoryQueryContainerInterface
{
    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryStoreQuery
     */
    public function queryCategoryStoreWithStoresByFkCategory(int $idCategory): SpyCategoryStoreQuery;
}
