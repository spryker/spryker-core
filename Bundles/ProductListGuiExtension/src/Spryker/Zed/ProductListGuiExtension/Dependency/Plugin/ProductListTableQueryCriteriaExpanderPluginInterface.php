<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface ProductListTableQueryCriteriaExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands QueryCriteriaTransfer with additional criteria for expanding default query running in ProductListTable.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandProductListQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer;
}
