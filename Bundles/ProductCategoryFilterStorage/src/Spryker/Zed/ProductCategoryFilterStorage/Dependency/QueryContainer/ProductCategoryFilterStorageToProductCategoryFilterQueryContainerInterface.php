<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Dependency\QueryContainer;

interface ProductCategoryFilterStorageToProductCategoryFilterQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\ProductCategoryFilter\Persistence\SpyProductCategoryFilterQuery
     */
    public function queryProductCategoryFilter();

}
