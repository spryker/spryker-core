<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence;

use Generated\Shared\Transfer\RuleQueryTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\QueryPropelRule\Persistence\QueryPropelRulePersistenceFactory getFactory()
 */
class QueryPropelRuleQueryContainer extends AbstractQueryContainer implements QueryPropelRuleQueryContainerInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ModelCriteria $query, RuleQueryTransfer $ruleQueryTransfer)
    {
        return $this->getFactory()
            ->createQueryBuilder()
            ->buildQuery($query, $ruleQueryTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $json
     *
     * @return \Generated\Shared\Transfer\RuleQuerySetTransfer
     */
    public function createRuleSetFromJson($json)
    {
        return $this->getFactory()
            ->createRuleTransferMapper()
            ->createRuleQuerySetFromJson($json);
    }

}
