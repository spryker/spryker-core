<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence;

use Generated\Shared\Transfer\RuleQueryTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface QueryPropelRuleQueryContainerInterface extends QueryContainerInterface
{

    /**
     * Specification:
     * - Converts RuleQuerySet of RuleQueryTransfer into Propel criteria
     * - Combines criteria with Propel query
     * - Returns combined Propel query
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, RuleQueryTransfer $ruleQueryTransfer);

    /**
     * Specification:
     * - Converts json string into an array
     * - Creates RuleQuerySetTransfer using the array as parameter
     *
     * @api
     *
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\RuleQuerySetTransfer
     */
    public function createRuleSetFromJson($json);

}
