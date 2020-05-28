<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Business;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface PropelQueryBuilderFacadeInterface
{
    /**
     * Specification:
     * - Maps given QueryCriteriaTransfer to PropelQueryBuilderCriteriaTransfer.
     * - Expands given propel query with mapped criteria.
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query, QueryCriteriaTransfer $queryCriteriaTransfer): ModelCriteria;
}
