<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\MetaData;

use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;

class MetaDataProvider implements MetaDataProviderInterface
{

    /**
     *
     * @var array|\Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]|\Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected $specificationPlugins = [];

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected $comparatorOperators;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    protected $logicalComparators;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]|\Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface $specificationPlugins
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators $comparatorOperators
     * @param \Spryker\Zed\Discount\Business\QueryString\LogicalComparators $logicalComparators
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
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return array|\string[]
     *
     */
    public function getAcceptedTypesByFieldName($fieldName)
    {
        foreach ($this->specificationPlugins as $specificationPlugins) {
            if ($fieldName === $specificationPlugins->getFieldName()) {
                return $specificationPlugins->acceptedDataTypes();
            }
        }

        throw new QueryStringException(
            'No specification plugin found for "%s" field.',
            $fieldName
        );
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
