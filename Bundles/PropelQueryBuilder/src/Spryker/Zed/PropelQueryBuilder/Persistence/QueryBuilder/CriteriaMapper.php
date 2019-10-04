<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder;

use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface;
use Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface;

class CriteriaMapper implements CriteriaMapperInterface
{
    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\OperatorBuilderInterface
     */
    protected $operatorBuilder;

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface
     */
    protected $jsonCriterionMapper;

    /**
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\OperatorBuilderInterface $operatorBuilder
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\JsonMapper\JsonCriterionMapperInterface $jsonCriterionMapper
     */
    public function __construct(OperatorBuilderInterface $operatorBuilder, JsonCriterionMapperInterface $jsonCriterionMapper)
    {
        $this->operatorBuilder = $operatorBuilder;
        $this->jsonCriterionMapper = $jsonCriterionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function toCriteria(PropelQueryBuilderCriteriaTransfer $propelQueryBuilderCriteriaTransfer)
    {
        $criteria = $this->createCriteria();
        $mappings = $this->remapFieldAliases($propelQueryBuilderCriteriaTransfer);

        return $this->appendCriteria(
            $criteria,
            $propelQueryBuilderCriteriaTransfer->getRuleSet(),
            $propelQueryBuilderCriteriaTransfer->getRuleSet()->getCondition(),
            $mappings
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $currentRuleSetTransfer
     * @param string $condition
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function appendCriteria(
        ModelCriteria $criteria,
        PropelQueryBuilderRuleSetTransfer $currentRuleSetTransfer,
        $condition,
        array $mappings = []
    ) {
        $criterionCollection = [];
        foreach ($currentRuleSetTransfer->getRules() as $ruleSetTransfer) {
            if (count((array)$ruleSetTransfer->getRules()) > 0) {
                $criteria = $this->appendCriteria(
                    $criteria,
                    $ruleSetTransfer,
                    $currentRuleSetTransfer->getCondition(),
                    $mappings
                );
            } else {
                $criterion = $this->buildCriterion($criteria, $ruleSetTransfer, $mappings);
                $criterionCollection[] = $criterion;
            }
        }

        $criterionSet = $this->combineCriterionSet($currentRuleSetTransfer, $criterionCollection);
        if ($criterionSet) {
            $criteria = $this->appendCriterionSet($criteria, $criterionSet, $condition);
        }

        return $criteria;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion
     */
    protected function buildCriterion(
        ModelCriteria $criteria,
        PropelQueryBuilderRuleSetTransfer $ruleSetTransfer,
        array $mappings = []
    ) {
        $operator = $this->operatorToPropelOperator($ruleSetTransfer->getOperator());
        $mappedFields = $this->getMappedFields($ruleSetTransfer->getId(), $mappings);

        if (count($mappedFields) >= 1) {
            $combinedCriterion = $this->createCombinedCriterion($criteria, $ruleSetTransfer, $operator, $mappings);

            if ($combinedCriterion) {
                return $combinedCriterion;
            }
        }

        return $this->createCriterion($criteria, $ruleSetTransfer, $operator);
    }

    /**
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion[] $criterionCollection
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null
     */
    protected function combineCriterionSet(PropelQueryBuilderRuleSetTransfer $ruleSetTransfer, array $criterionCollection)
    {
        $mainCriterion = array_shift($criterionCollection);
        if (!$mainCriterion) {
            return $mainCriterion;
        }

        $isOrCondition = $this->isOrCondition($ruleSetTransfer->getCondition());
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
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null
     */
    protected function createCriterion(
        ModelCriteria $criteria,
        PropelQueryBuilderRuleSetTransfer $ruleSetTransfer,
        OperatorInterface $operator
    ) {
        if ($this->jsonCriterionMapper->isJsonAttribute($ruleSetTransfer)) {
            return $this->jsonCriterionMapper->createJsonCriterion(
                $criteria,
                $ruleSetTransfer,
                $operator
            );
        }

        return $criteria->getNewCriterion(
            $ruleSetTransfer->getField(),
            $operator->getValue($ruleSetTransfer),
            $operator->getOperator()
        );
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     * @param \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $ruleSetTransfer
     * @param \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface $operator
     * @param array $mappings
     *
     * @return \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null
     */
    protected function createCombinedCriterion(
        ModelCriteria $criteria,
        PropelQueryBuilderRuleSetTransfer $ruleSetTransfer,
        OperatorInterface $operator,
        array $mappings = []
    ) {
        /** @var \Propel\Runtime\ActiveQuery\Criterion\AbstractCriterion|null $lastCriterion */
        $lastCriterion = null;

        $mappedFields = $this->getMappedFields($ruleSetTransfer->getId(), $mappings);
        foreach ($mappedFields as $field) {
            $multiRule = clone $ruleSetTransfer;
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
     * @return \Spryker\Zed\PropelQueryBuilder\Persistence\QueryBuilder\Operator\OperatorInterface
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
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $ruleQueryTransfer
     *
     * @return array
     */
    protected function remapFieldAliases(PropelQueryBuilderCriteriaTransfer $ruleQueryTransfer)
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
     * @return bool
     */
    protected function isOrCondition($value)
    {
        return strtoupper($value) === Criteria::LOGICAL_OR;
    }
}
