<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder;

use Generated\Shared\Transfer\RuleQueryTransfer;

interface CriteriaMapperInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQuery
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function toCriteria(RuleQueryTransfer $ruleQuery);

}
