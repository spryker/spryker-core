<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder;

use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Generated\Shared\Transfer\RuleQueryTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface;
use Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface;

class CriteriaMapper implements CriteriaMapperInterface
{

    /**
     * @var \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\OperatorBuilderInterface
     */
    protected $operatorBuilder;

    /**
     * @var \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface
     */
    protected $jsonCriterionMapper;

    /**
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\OperatorBuilderInterface $operatorBuilder
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface $jsonCriterionMapper
     */
    public function __construct(OperatorBuilderInterface $operatorBuilder, JsonCriterionMapperInterface $jsonCriterionMapper)
    {
        $this->operatorBuilder = $operatorBuilder;
        $this->jsonCriterionMapper = $jsonCriterionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQuery
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function toCriteria(RuleQueryTransfer $ruleQuery)
    {
        $criteria = $this->createCriteria();

        return $this->appendRuleCriteria($criteria, $ruleQuery, $ruleQuery->getMappings());
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQuery
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function appendRuleCriteria(ModelCriteria $criteria, RuleQueryTransfer $ruleQuery, $mappings = [])
    {
        return $this->appendCriteria(
            $criteria,
            $ruleQuery->getRuleSet(),
            $ruleQuery->getRuleSet()->getCondition(),
            $mappings
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySet
     * @param string $condition
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function appendCriteria(
        ModelCriteria $criteria,
        RuleQuerySetTransfer $ruleQuerySet,
        $condition,
        array $mappings = []
    ) {
        $criterionCollection = [];
        foreach ($ruleQuerySet->getRules() as $ruleSet) {
            if (count((array)$ruleSet->getRules()) > 0) {
                $criteria = $this->appendCriteria(
                    $criteria,
                    $ruleSet,
                    $ruleQuerySet->getCondition(),
                    $mappings
                );
            } else {
                $criterion = $this->buildCriterion($criteria, $ruleSet, $mappings);
                $criterionCollection[] = $criterion;
            }
        }

        $criterionSet = $this->combineCriterionSet($ruleQuerySet, $criterionCollection);
        if ($criterionSet) {
            $criteria = $this->appendCriterionSet($criteria, $criterionSet, $condition);
        }

        return $criteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function buildCriterion(ModelCriteria $criteria, RuleQuerySetTransfer $rule, array $mappings = [])
    {
        $operator = $this->operatorToPropelOperator($rule->getOperator());
        $fields = $this->getMappedFields($rule->getId(), $mappings);

        if (count($fields) >= 1) {
            $combinedCriterion = $this->createCombinedCriterion($criteria, $rule, $operator, $mappings);

            if ($combinedCriterion) {
                return $combinedCriterion;
            }
        }

        return $this->createCriterion($criteria, $rule, $operator);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySet
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion[] $criterionCollection
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null
     */
    protected function combineCriterionSet(RuleQuerySetTransfer $ruleQuerySet, array $criterionCollection)
    {
        $mainCriterion = array_shift($criterionCollection);
        if (!$mainCriterion) {
            return $mainCriterion;
        }

        $isOrCondition = $this->isOrCondition($ruleQuerySet->getCondition());
        foreach ($criterionCollection as $criterion) {
            if ($isOrCondition) {
                $mainCriterion->addOr($criterion);
            } else {
                $mainCriterion->addAnd($criterion);
            }
        }

        return $mainCriterion;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $criterionSet
     * @param string $condition
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function appendCriterionSet(ModelCriteria $criteria, AbstractCriterion $criterionSet, $condition)
    {
        $isOrCondition = $this->isOrCondition($condition);
        if ($isOrCondition) {
            $criteria->addOr($criterionSet);
        } else {
            $criteria->addAnd($criterionSet);
        }

        return $criteria;
    }

    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function createCriteria()
    {
        return new ModelCriteria();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @return null|\Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function createCriterion(ModelCriteria $criteria, RuleQuerySetTransfer $rule, OperatorInterface $operator)
    {
        if ($this->jsonCriterionMapper->isJsonAttribute($rule)) {
            return $this->jsonCriterionMapper->createJsonCriterion(
                $criteria,
                $rule,
                $operator
            );
        }

        return $criteria->getNewCriterion(
            $rule->getField(),
            $operator->getValue($rule),
            $operator->getOperator()
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $rule
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param array $mappings
     *
     * @return null|\Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function createCombinedCriterion(
        ModelCriteria $criteria,
        RuleQuerySetTransfer $rule,
        OperatorInterface $operator,
        array $mappings = []
    ) {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $lastCriterion */
        $lastCriterion = null;

        $mappedFields = $this->getMappedFields($rule->getId(), $mappings);
        foreach ($mappedFields as $field) {
            $multiRule = clone $rule;
            $multiRule->setField($field);

            $criterion = $this->createCriterion($criteria, $multiRule, $operator);
            if (!$lastCriterion) {
                $lastCriterion = $criterion;
                continue;
            }

            $lastCriterion->addOr($criterion);
        }

        return $lastCriterion;
    }

    /**
     * @param string $operatorType
     *
     * @return \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface
     */
    protected function operatorToPropelOperator($operatorType)
    {
        return $this->operatorBuilder->buildOperatorByType($operatorType);
    }

    /**
     * @param string $key
     * @param array $mappings
     *
     * @return array
     */
    protected function getMappedFields($key, array $mappings)
    {
        if (empty($mappings) || !array_key_exists($key, $mappings)) {
            return [];
        }

        return $mappings[$key];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function isOrCondition($value)
    {
        return strtoupper($value) === Criteria::LOGICAL_OR;
    }

}
