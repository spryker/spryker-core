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
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function toCriteria(RuleQueryTransfer $ruleQueryTransfer)
    {
        $criteria = $this->createCriteria();
        $mappings = $this->remapFieldAliases($ruleQueryTransfer);

        return $this->appendCriteria(
            $criteria,
            $ruleQueryTransfer->getRuleSet(),
            $ruleQueryTransfer->getRuleSet()->getCondition(),
            $mappings
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySetTransfer
     * @param string $condition
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function appendCriteria(
        ModelCriteria $criteria,
        RuleQuerySetTransfer $ruleQuerySetTransfer,
        $condition,
        array $mappings = []
    ) {
        $criterionCollection = [];
        foreach ($ruleQuerySetTransfer->getRules() as $ruleSetTransfer) {
            if (count((array)$ruleSetTransfer->getRules()) > 0) {
                $criteria = $this->appendCriteria(
                    $criteria,
                    $ruleSetTransfer,
                    $ruleQuerySetTransfer->getCondition(),
                    $mappings
                );
            } else {
                $criterion = $this->buildCriterion($criteria, $ruleSetTransfer, $mappings);
                $criterionCollection[] = $criterion;
            }
        }

        $criterionSet = $this->combineCriterionSet($ruleQuerySetTransfer, $criterionCollection);
        if ($criterionSet) {
            $criteria = $this->appendCriterionSet($criteria, $criterionSet, $condition);
        }

        return $criteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySetTransfer
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function buildCriterion(ModelCriteria $criteria, RuleQuerySetTransfer $ruleQuerySetTransfer, array $mappings = [])
    {
        $operator = $this->operatorToPropelOperator($ruleQuerySetTransfer->getOperator());
        $mappedFields = $this->getMappedFields($ruleQuerySetTransfer->getId(), $mappings);

        if (count($mappedFields) >= 1) {
            $combinedCriterion = $this->createCombinedCriterion($criteria, $ruleQuerySetTransfer, $operator, $mappings);

            if ($combinedCriterion) {
                return $combinedCriterion;
            }
        }

        return $this->createCriterion($criteria, $ruleQuerySetTransfer, $operator);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySetTransfer
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion[] $criterionCollection
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null
     */
    protected function combineCriterionSet(RuleQuerySetTransfer $ruleQuerySetTransfer, array $criterionCollection)
    {
        $mainCriterion = array_shift($criterionCollection);
        if (!$mainCriterion) {
            return $mainCriterion;
        }

        $isOrCondition = $this->isOrCondition($ruleQuerySetTransfer->getCondition());
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
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySetTransfer
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @return null|\Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function createCriterion(ModelCriteria $criteria, RuleQuerySetTransfer $ruleQuerySetTransfer, OperatorInterface $operator)
    {
        if ($this->jsonCriterionMapper->isJsonAttribute($ruleQuerySetTransfer)) {
            return $this->jsonCriterionMapper->createJsonCriterion(
                $criteria,
                $ruleQuerySetTransfer,
                $operator
            );
        }

        return $criteria->getNewCriterion(
            $ruleQuerySetTransfer->getField(),
            $operator->getValue($ruleQuerySetTransfer),
            $operator->getOperator()
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\RuleQuerySetTransfer $ruleQuerySetTransfer
     * @param \Spryker\Zed\QueryPropelRule\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param array $mappings
     *
     * @return null|\Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function createCombinedCriterion(
        ModelCriteria $criteria,
        RuleQuerySetTransfer $ruleQuerySetTransfer,
        OperatorInterface $operator,
        array $mappings = []
    ) {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion $lastCriterion */
        $lastCriterion = null;

        $mappedFields = $this->getMappedFields($ruleQuerySetTransfer->getId(), $mappings);
        foreach ($mappedFields as $field) {
            $multiRule = clone $ruleQuerySetTransfer;
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
     * @param \Generated\Shared\Transfer\RuleQueryTransfer $ruleQueryTransfer
     *
     * @return array
     */
    protected function remapFieldAliases(RuleQueryTransfer $ruleQueryTransfer)
    {
        $result = [];
        foreach ($ruleQueryTransfer->getMappings() as $mappingTransfer) {
            $result[$mappingTransfer->getAlias()] = $mappingTransfer->getColumns();
        }

        return $result;
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
