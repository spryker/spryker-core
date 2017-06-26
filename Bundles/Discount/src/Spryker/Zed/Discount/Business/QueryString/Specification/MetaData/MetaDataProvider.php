<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\MetaData;

use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithAttributesPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;

class MetaDataProvider implements MetaDataProviderInterface
{

    /**
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
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]|\Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface[] $specificationPlugins
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
     * @return string[]
     */
    public function getAvailableFields()
    {
        $queryStringFields = [];
        foreach ($this->specificationPlugins as $specificationPlugin) {
            if ($specificationPlugin instanceof DiscountRuleWithAttributesPluginInterface) {
                $queryStringFields = array_merge(
                    $queryStringFields,
                    $this->getAttributeTypes($specificationPlugin)
                );
            } else {
                $queryStringFields[] = $specificationPlugin->getFieldName();
            }
        }

        return $queryStringFields;
    }

    /**
     * @param string $fieldName
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     *
     * @return string[]
     */
    public function getAcceptedTypesByFieldName($fieldName)
    {
        if (strpos($fieldName, '.') !== false) {
            list($fieldName, $attribute) = explode('.', $fieldName);
        }

        foreach ($this->specificationPlugins as $specificationPlugin) {
            if ($fieldName === $specificationPlugin->getFieldName()) {
                return $specificationPlugin->acceptedDataTypes();
            }
        }

        throw new QueryStringException(sprintf(
            'No specification plugin found for "%s" field. Have you added it to DiscountDependencyProvider::getCollectorPlugins or getDecisionRulePlugins respectively?',
            $fieldName
        ));
    }

    /**
     * @param string $fieldName
     *
     * @return string[]
     */
    public function getAvailableOperatorExpressionsForField($fieldName)
    {
        $acceptedTypes = $this->getAcceptedTypesByFieldName($fieldName);

        return $this->comparatorOperators->getOperatorExpressionsByTypes($acceptedTypes);
    }

    /**
     * @return string[]
     */
    public function getAvailableComparatorExpressions()
    {
        return $this->comparatorOperators->getAvailableComparatorExpressions();
    }

    /**
     * @return string[]
     */
    public function getLogicalComparators()
    {
        return $this->logicalComparators->getLogicalOperators();
    }

    /**
     * @return string[]
     */
    public function getCompoundExpressions()
    {
        return $this->comparatorOperators->getCompoundComparatorExpressions();
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface|\Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithAttributesPluginInterface $specificationPlugin
     *
     * @return array
     */
    protected function getAttributeTypes($specificationPlugin)
    {
        $attributeFields = [];
        foreach ($specificationPlugin->getAttributeTypes() as $attributeType) {
            $attributeFields[] = $specificationPlugin->getFieldName() . '.' . $attributeType;
        }
        return $attributeFields;
    }

    /**
     * @return array
     */
    public function getQueryStringValueOptions()
    {
        $valueOptions = [];
        foreach ($this->specificationPlugins as $specificationPlugin) {
            if ($specificationPlugin instanceof DiscountRuleWithValueOptionsPluginInterface) {
                $valueOptions = array_merge($valueOptions, $specificationPlugin->getQueryStringValueOptions());
            }
        }

        return $valueOptions;
    }

}
