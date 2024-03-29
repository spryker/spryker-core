<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Constraint;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup\AbstractGlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface;
use Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ThresholdStrategyConstraintValidator extends ConstraintValidator
{
    /**
     * @var string
     */
    protected const MESSAGE_UPDATE_SOFT_STRATEGY_ERROR = 'To save {{strategy_group}} threshold - enter value that is higher than 0 in this field. To delete threshold set all fields equal to 0 or left them empty and save.';

    /**
     * @var string
     */
    protected const MESSAGE_KEY = '{{strategy_group}}';

    /**
     * @param string $value
     * @param \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint $constraint
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

        if ($formData[AbstractGlobalThresholdType::FIELD_THRESHOLD]) {
            return;
        }

        $fieldsToCheck = [];
        $thresholdGroup = '';
        $salesOrderThresholdFormExpanderPlugins = $constraint->getSalesOrderThresholdFormExpanderPlugins();

        /** @var \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface&\Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface $salesOrderThresholdFormExpanderPlugin */
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
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface $salesOrderThresholdFormExpanderPlugin
     * @param array $formData
     *
     * @return bool
     */
    protected function isPluginApplicable(
        SalesOrderThresholdFormExpanderPluginInterface $salesOrderThresholdFormExpanderPlugin,
        array $formData
    ): bool {
        if (
            !$salesOrderThresholdFormExpanderPlugin instanceof SalesOrderThresholdFormFieldDependenciesPluginInterface
            || $salesOrderThresholdFormExpanderPlugin->getThresholdKey() !== $formData[AbstractGlobalThresholdType::FIELD_STRATEGY]
            || !$salesOrderThresholdFormExpanderPlugin->getThresholdFieldDependentFieldNames()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param array<string> $fields
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
