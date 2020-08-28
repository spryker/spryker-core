<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\MySql;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface;

class JsonMapper implements JsonMapperInterface
{
    protected const JSON_EXTRACT_PATTERN = "JSON_EXTRACT(%s, '$.%s') %s '%s'";

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param string $attributeName
     *
     * @return string
     */
    public function getField(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer, OperatorInterface $operator, $attributeName)
    {
        return $ruleSetTransfer->getField();
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param string $attributeName
     *
     * @return mixed
     */
    public function getValue(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer, OperatorInterface $operator, $attributeName)
    {
        return sprintf(
            static::JSON_EXTRACT_PATTERN,
            $ruleSetTransfer->getField(),
            $attributeName,
            Criteria::EQUAL,
            $operator->getValue($ruleSetTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param string $attributeName
     *
     * @return string
     */
    public function getOperator(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer, OperatorInterface $operator, $attributeName)
    {
        return Criteria::CUSTOM;
    }
}
