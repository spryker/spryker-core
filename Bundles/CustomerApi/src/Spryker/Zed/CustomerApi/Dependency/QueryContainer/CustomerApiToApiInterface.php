<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface CustomerApiToApiInterface
{

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data);

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem($data);

}
