<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product\Price;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\ProductManagementConfig getConfig()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class ProductMoneyType extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_FK_PRICE_TYPE = 'fk_price_type';

    /**
     * @uses \Generated\Shared\Transfer\PriceProductTransfer::MONEY_VALUE
     *
     * @var string
     */
    protected const FIELD_MONEY_VALUE = 'moneyValue';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addFkPriceTypeField($builder)
            ->addMoneyValueField($builder, $options);
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
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $viewData = $form->getViewData();
        $view->vars['price_type'] = $viewData->getPriceType();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkPriceTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_PRICE_TYPE, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addMoneyValueField(FormBuilderInterface $builder, array $options)
    {
        $moneyFormType = $this->getFactory()->getMoneyFormTypePlugin()->getType();

        $builder->add(
            static::FIELD_MONEY_VALUE,
            $moneyFormType,
            [
                'data_class' => MoneyValueTransfer::class,
            ],
        );

        $this->expandMoneyValueFieldWithLocale($builder, $options, $moneyFormType);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     * @param string $moneyFormType
     *
     * @return void
     */
    protected function expandMoneyValueFieldWithLocale(
        FormBuilderInterface $builder,
        array $options,
        string $moneyFormType
    ): void {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options, $moneyFormType) {
            $form = $event->getForm();

            $moneyValueFieldConfig = $form->get(static::FIELD_MONEY_VALUE)->getConfig();
            if (!$moneyValueFieldConfig->hasOption(static::OPTION_LOCALE)) {
                return;
            }

            $moneyValueFieldOptions = $moneyValueFieldConfig->getOptions();
            $moneyValueFieldOptions[static::OPTION_LOCALE] = $options[static::OPTION_LOCALE];

            $form->remove(static::FIELD_MONEY_VALUE);
            $form->add(static::FIELD_MONEY_VALUE, $moneyFormType, $moneyValueFieldOptions);
        });
    }
}
