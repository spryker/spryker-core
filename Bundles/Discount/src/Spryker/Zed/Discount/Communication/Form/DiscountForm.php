<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form;

use Generated\Shared\Transfer\DiscountCalculatorTransfer;
use Generated\Shared\Transfer\DiscountConditionTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class DiscountForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addGeneralSubForm($builder, $options)
            ->addCalculatorSubForm($builder, $options)
            ->addConditionsSubForm($builder, $options);

        $this->executeFormTypeExpanderPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function executeFormTypeExpanderPlugins(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        foreach ($this->getFactory()->getDiscountFormTypeExpanderPlugins() as $calculatorFormTypeExpanderPlugin) {
            $calculatorFormTypeExpanderPlugin->expandFormType($builder, $options);
        }

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return $this
     */
    protected function addGeneralSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            DiscountConfiguratorTransfer::DISCOUNT_GENERAL,
            GeneralForm::class,
            [
                'data_class' => DiscountGeneralTransfer::class,
                'label' => false,
                'locale' => $options[static::OPTION_LOCALE],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return $this
     */
    protected function addCalculatorSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            DiscountConfiguratorTransfer::DISCOUNT_CALCULATOR,
            CalculatorForm::class,
            [
                'data_class' => DiscountCalculatorTransfer::class,
                'label' => false,
                'locale' => $options[static::OPTION_LOCALE],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return $this
     */
    protected function addConditionsSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            DiscountConfiguratorTransfer::DISCOUNT_CONDITION,
            ConditionsForm::class,
            [
                'data_class' => DiscountConditionTransfer::class,
                'label' => false,
                'locale' => $options[static::OPTION_LOCALE],
            ],
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'discount';
    }
}
