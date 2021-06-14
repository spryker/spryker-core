<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QueryCriteriaTransfer;

/**
 * Allows to expand query criteria for expanding default query running in ProductOptionListTable.
 */
interface ProductOptionListTableQueryCriteriaExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands `QueryCriteriaTransfer` with additional criteria for expanding default query running in `ProductOptionListTable`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function expandQueryCriteria(QueryCriteriaTransfer $queryCriteriaTransfer): QueryCriteriaTransfer;
}
