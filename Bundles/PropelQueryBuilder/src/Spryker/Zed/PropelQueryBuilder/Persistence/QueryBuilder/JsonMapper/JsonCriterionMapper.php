<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper;

use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\PropelQueryBuilder\Persistence\Exception\QueryBuilderException;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface;

class JsonCriterionMapper implements JsonCriterionMapperInterface
{
    /**
     * @var string
     */
    protected $pattern = '/^([^.]*).(json).([^.]*)$/i';

    /**
     * @var string
     */
    protected $replacement = '$3';

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface
     */
    protected $criterionMapper;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface $criterionMapper
     */
    public function __construct(JsonMapperInterface $criterionMapper)
    {
        $this->criterionMapper = $criterionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     *
     * @return string|null
     */
    public function getAttributeName(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer)
    {
        $name = trim(preg_replace(
            $this->pattern,
            $this->replacement,
            $ruleSetTransfer->getId()
        ));

        if ($name === $ruleSetTransfer->getId()) {
            return null;
        }

        return $name;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     *
     * @return bool
     */
    public function isJsonAttribute(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer)
    {
        return $this->getAttributeName($ruleSetTransfer) !== null;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @throws \Spryker\Zed\PropelQueryBuilder\Persistence\Exception\QueryBuilderException
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    public function createJsonCriterion(
        ModelCriteria $criteria,
        PropelQueryBuilderRuleSetTransfer $ruleSetTransfer,
        OperatorInterface $operator
    ) {
        if (!$this->isJsonAttribute($ruleSetTransfer)) {
            throw new QueryBuilderException('Expected json attribute for PropelQueryBuilderRuleSet with id: ' . $ruleSetTransfer->getId());
        }

        $attributeName = $this->getAttributeName($ruleSetTransfer);
        $field = $this->criterionMapper->getField($ruleSetTransfer, $operator, $attributeName);
        $value = $this->criterionMapper->getValue($ruleSetTransfer, $operator, $attributeName);
        $operatorExpression = $this->criterionMapper->getOperator($ruleSetTransfer, $operator, $attributeName);

        return $criteria->getNewCriterion(
            $field,
            $value,
            $operatorExpression
        );
    }
}
