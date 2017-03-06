<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder;

use Generated\Shared\Transfer\RuleQueryTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface QueryBuilderInterface
{

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildQuery(ModelCriteria $query, RuleQueryTransfer $ruleQueryTransfer);

}
