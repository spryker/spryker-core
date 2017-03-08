<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper;

use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\QueryPropelRule\Persistence\Exception\QueryBuilderException;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface;

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
     * @var \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface
     */
    protected $criterionMapper;

    /**
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonMapperInterface $criterionMapper
     */
    public function __construct(JsonMapperInterface $criterionMapper)
    {
        $this->criterionMapper = $criterionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     *
     * @return string|null
     */
    public function getAttributeName(RuleQuerySetTransfer $rule)
    {
        $name = trim(preg_replace(
            $this->pattern,
            $this->replacement,
            $rule->getId()
        ));

        if ($name === $rule->getId()) {
            return null;
        }

        return $name;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     *
     * @return bool
     */
    public function isJsonAttribute(RuleQuerySetTransfer $rule)
    {
        return trim($this->getAttributeName($rule)) !== '';
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @throws \Spryker\Zed\QueryPropelRule\Persistence\Exception\QueryBuilderException
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    public function createJsonCriterion(ModelCriteria $criteria, RuleQuerySetTransfer $rule, OperatorInterface $operator)
    {
        if (!$this->isJsonAttribute($rule)) {
            throw new QueryBuilderException('Expected json attribute for RuleQuerySet with id: ' . $rule->getId());
        }

        $attributeName = $this->getAttributeName($rule);
        $field = $this->criterionMapper->getField($rule, $operator, $attributeName);
        $value = $this->criterionMapper->getValue($rule, $operator, $attributeName);
        $operatorExpression = $this->criterionMapper->getOperator($rule, $operator, $attributeName);

        return $criteria->getNewCriterion(
            $field,
            $value,
            $operatorExpression
        );
    }

}
