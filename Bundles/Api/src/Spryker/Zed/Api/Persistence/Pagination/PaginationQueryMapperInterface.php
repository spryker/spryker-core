<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence\Pagination;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainer getQueryContainer()
 */
interface PaginationQueryMapperInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapPagination(ModelCriteria $query, ApiPaginationTransfer $apiPaginationTransfer);

}
