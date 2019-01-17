<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Type\ThresholdGroup;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\LocalizedMessagesType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\MerchantRelationshipThresholdType;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\MerchantRelationshipSalesOrderThresholdGuiConfig getConfig()
 */
abstract class AbstractMerchantRelationshipThresholdType extends AbstractType
{
    public const FIELD_ID_THRESHOLD = 'idThreshold';
    public const FIELD_STRATEGY = 'strategy';
    public const FIELD_THRESHOLD = 'threshold';

    protected const MESSAGE_UPDATE_SOFT_STRATEGY_ERROR = 'To save {{strategy_group}} threshold - enter value that is higher than 0 in this field. To delete threshold set all fields equal to 0 or left them empty and save.';
    protected const MESSAGE_KEY = '{{strategy_group}}';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(MerchantRelationshipThresholdType::OPTION_CURRENCY_CODE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addStrategyField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_STRATEGY, ChoiceType::class, [
            'label' => false,
            'choices' => $choices,
            'required' => false,
            'expanded' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addThresholdValueField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_THRESHOLD, MoneyType::class, [
            'label' => 'Enter threshold value',
            'currency' => $options[MerchantRelationshipThresholdType::OPTION_CURRENCY_CODE],
            'divisor' => 100,
            'constraints' => [
                new Range(['min' => 0]),
                $this->createDepenedentFieldsConstraint(),
            ],
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocalizedForms(FormBuilderInterface $builder)
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $this->addLocalizedForm($builder, $localeTransfer->getLocaleName());
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $name
     * @param array $options
     *
     * @return $this
     */
    protected function addLocalizedForm(FormBuilderInterface $builder, string $name, array $options = [])
    {
        $builder->add($name, LocalizedMessagesType::class, [
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Callback
     */
    protected function createDepenedentFieldsConstraint()
    {
        return new Callback(function ($value, ExecutionContextInterface $context) {
            /** @var \Symfony\Component\Form\Form $form */
            $form = $context->getObject();
            $parentThresholdGroupForm = $form->getParent()->getParent();
            $data = $form->getParent()->getData();

            $this->checkStrategy($data, $context);
        });
    }

    /**
     * @param array $data
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    protected function checkStrategy(array $data, ExecutionContextInterface $context): void
    {
        $plugins = $this->getFactory()->getSalesOrderThresholdFormExpanderPlugins();

        foreach ($plugins as $plugin) {
            if (!$plugin instanceof  SalesOrderThresholdFormFieldDependenciesPluginInterface) {
                continue;
            }

            /** @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface $plugin */
            if ($plugin->getThresholdKey() !== $data[static::FIELD_STRATEGY] || !$plugin->getThresholdFieldDependentFieldNames()) {
                continue;
            }

            foreach ($plugin->getThresholdFieldDependentFieldNames() as $field) {
                if ($data[$field] && !$data[static::FIELD_THRESHOLD]) {
                    $message = strtr(static::MESSAGE_UPDATE_SOFT_STRATEGY_ERROR, [static::MESSAGE_KEY => $plugin->getThresholdGroup()]);
                    $context->addViolation($message);
                    return;
                }
            }
        }
    }
}
