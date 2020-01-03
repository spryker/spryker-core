<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Constraint;

use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractMerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ThresholdStrategyConstraintValidator extends ConstraintValidator
{
    protected const MESSAGE_UPDATE_SOFT_STRATEGY_ERROR = 'To save {{strategy_group}} threshold - enter value that is higher than 0 in this field. To delete threshold set all fields equal to 0 or left them empty and save.';
    protected const MESSAGE_KEY = '{{strategy_group}}';

    /**
     * @param string $value
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ThresholdStrategyConstraint) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\ThresholdStrategyConstraint');
        }

        $formData = $this->getFormData();

        if ($formData[AbstractMerchantRelationshipThresholdType::FIELD_THRESHOLD]) {
            return;
        }

        $fieldsToCheck = [];
        $thresholdGroup = '';
        $salesOrderThresholdFormExpanderPlugins = $constraint->getSalesOrderThresholdFormExpanderPlugins();

        foreach ($salesOrderThresholdFormExpanderPlugins as $salesOrderThresholdFormExpanderPlugin) {
            if (!$this->isPluginApplicable($salesOrderThresholdFormExpanderPlugin, $formData)) {
                continue;
            }

            $fieldsToCheck = array_merge($fieldsToCheck, $salesOrderThresholdFormExpanderPlugin->getThresholdFieldDependentFieldNames());
            $thresholdGroup = $salesOrderThresholdFormExpanderPlugin->getThresholdGroup();
        }

        if (!$this->isFieldsValid($fieldsToCheck, $formData)) {
            $message = strtr(static::MESSAGE_UPDATE_SOFT_STRATEGY_ERROR, [static::MESSAGE_KEY => $thresholdGroup]);
            $this->context->addViolation($message);
        }
    }

    /**
     * @param \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface $salesOrderThresholdFormExpanderPlugin
     * @param array $formData
     *
     * @return bool
     */
    protected function isPluginApplicable(
        SalesOrderThresholdFormExpanderPluginInterface $salesOrderThresholdFormExpanderPlugin,
        array $formData
    ): bool {
        if (!$salesOrderThresholdFormExpanderPlugin instanceof  SalesOrderThresholdFormFieldDependenciesPluginInterface
            || $salesOrderThresholdFormExpanderPlugin->getThresholdKey() !== $formData[AbstractMerchantRelationshipThresholdType::FIELD_STRATEGY]
            || !$salesOrderThresholdFormExpanderPlugin->getThresholdFieldDependentFieldNames()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string[] $fields
     * @param array $formData
     *
     * @return bool
     */
    protected function isFieldsValid(array $fields, array $formData): bool
    {
        foreach ($fields as $field) {
            if ($formData[$field]) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getFormData(): array
    {
        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->context->getObject();

        return $form->getParent()->getData();
    }
}
