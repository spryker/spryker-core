<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Dependency\QueryContainer;

interface CategoryPageSearchToCategoryQueryContainerInterface
{
    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function queryAllCategoryNodes();
}
