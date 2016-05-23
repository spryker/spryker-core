<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;

class MetaDataProvider
{
    /**
     * @var array|\Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]|CollectorPluginInterface[]
     */
    protected $specificationPlugins = [];

    /**
     * @var ComparatorOperators
     */
    protected $comparatorOperators;

    /**
     * @var LogicalComparators
     */
    protected $logicalComparators;

    /**
     * @param DecisionRulePluginInterface[]|CollectorPluginInterface $specificationPlugins
     * @param ComparatorOperators $comparatorOperators
     * @param LogicalComparators $logicalComparators
     */
    public function __construct(
        array $specificationPlugins,
        ComparatorOperators $comparatorOperators,
        LogicalComparators $logicalComparators
    ) {
        $this->specificationPlugins = $specificationPlugins;
        $this->comparatorOperators = $comparatorOperators;
        $this->logicalComparators = $logicalComparators;
    }

    /**
     * @return array|string[]
     */
    public function getAvailableFields()
    {
        $queryStringFields = [];
        foreach ($this->specificationPlugins as $specificationPlugin) {
            $queryStringFields[] = $specificationPlugin->getFieldName();
        }

        return $queryStringFields;
    }

    /**
     * @param string $fieldName
     *
     * @return array|string[]
     */
    public function getAcceptedTypesByFieldName($fieldName)
    {
        foreach ($this->specificationPlugins as $specificationPlugins) {
            if ($fieldName === $specificationPlugins->getFieldName()) {
                return $specificationPlugins->acceptedDataTypes();
            }
        }

    }

    /**
     * @param string $fieldName
     *
     * @return array|string[]
     */
    public function getAvailableOperatorExpressionsForField($fieldName)
    {
        $acceptedTypes = $this->getAcceptedTypesByFieldName($fieldName);

        return $this->comparatorOperators->getOperatorExpressionsByTypes($acceptedTypes);
    }

    /**
     * @return array|string[]
     */
    public function getAvailableComparatorExpressions()
    {
        return $this->comparatorOperators->getAvailableComparatorExpressions();
    }

    /**
     * @return array|string[]
     */
    public function getLogicalComparators()
    {
        return $this->logicalComparators->getLogicalOperators();
    }
}
