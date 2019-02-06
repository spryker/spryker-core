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
    protected const MESSAGE_UPDATE_SOFT_STRATEGY_ERROR = 'To save {{strategy_group}} threshold - enter value that is higher than 0 in this field. To delete threshold set all fields equal to 0 or left them empty and save.';
    protected const MESSAGE_KEY = '{{strategy_group}}';

    /**
     * @param string $value
     * @param \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ThresholdStrategyConstraint) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\ThresholdStrategyConstraint');
        }

        /** @var \Symfony\Component\Form\Form $form */
        $form = $this->context->getObject();
        $data = $form->getParent()->getData();
        $salesOrderThresholdFormExpanderPlugins = $constraint->getSalesOrderThresholdFormExpanderPlugins();

        foreach ($salesOrderThresholdFormExpanderPlugins as $salesOrderThresholdFormExpanderPlugin) {
            if (!$this->isValidPlugin($salesOrderThresholdFormExpanderPlugin, $data)) {
                continue;
            }

            $fields = $salesOrderThresholdFormExpanderPlugin->getThresholdFieldDependentFieldNames();

            foreach ($fields as $field) {
                if ($data[$field] && !$data[AbstractGlobalThresholdType::FIELD_THRESHOLD]) {
                    $this->createErrorMessage($salesOrderThresholdFormExpanderPlugin);
                    return;
                }
            }
        }
    }

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface $plugin
     * @param array $data
     *
     * @return bool
     */
    protected function isValidPlugin(SalesOrderThresholdFormExpanderPluginInterface $plugin, array $data): bool
    {
        if (!$plugin instanceof  SalesOrderThresholdFormFieldDependenciesPluginInterface
            || $plugin->getThresholdKey() !== $data[AbstractGlobalThresholdType::FIELD_STRATEGY]
        ) {
            return false;
        }

        if (!$plugin->getThresholdFieldDependentFieldNames()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface $plugin
     *
     * @return void
     */
    protected function createErrorMessage(SalesOrderThresholdFormExpanderPluginInterface $plugin): void
    {
        $message = strtr(static::MESSAGE_UPDATE_SOFT_STRATEGY_ERROR, [
            static::MESSAGE_KEY => $plugin->getThresholdGroup(),
        ]);
        $this->context->addViolation($message);
    }
}
