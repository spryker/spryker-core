<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface PropelQueryBuilderQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - Converts RuleQuerySet of PropelQueryBuilderCriteriaTransfer into Propel criteria
     * - Combines criteria with Propel query
     * - Returns combined Propel query
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, PropelQueryBuilderCriteriaTransfer $criteriaTransfer);

    /**
     * Specification:
     * - Converts json string into an array
     * - Creates PropelQueryBuilderRuleSetTransfer using the array as parameter
     *
     * @api
     *
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    public function createPropelQueryBuilderCriteriaFromJson($json);
}
