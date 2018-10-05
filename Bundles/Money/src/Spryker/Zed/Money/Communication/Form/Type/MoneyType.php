<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\Type;

use Exception;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 */
class MoneyType extends AbstractType
{
    public const FIELD_NET_AMOUNT = 'net_amount';
    public const FIELD_GROSS_AMOUNT = 'gross_amount';
    public const FIELD_FK_CURRENCY = 'fk_currency';
    public const FIELD_FK_STORE = 'fk_store';

    public const MAX_MONEY_INT = 21474835;
    public const MIN_MONEY_INT = 0;

    public const OPTION_VALIDATION_GROUPS = 'validation_groups';

    public const REGULAR_EXPRESSION_MONEY_VALUE = '/[0-9\.\,]+/';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $validationGroups = $options[static::OPTION_VALIDATION_GROUPS];
        $this->addFieldAmount($builder, static::FIELD_NET_AMOUNT, $validationGroups)
            ->addFieldAmount($builder, static::FIELD_GROSS_AMOUNT, $validationGroups)
            ->addFieldFkCurrency($builder)
            ->addFieldFkStore($builder);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($validationGroups) {
                $moneyCurrencyOptions = $this->getFactory()
                    ->createMoneyDataProvider()
                    ->getMoneyCurrencyOptionsFor($event->getData());

                $this->configureMoneyInputs(
                    $event->getForm(),
                    static::FIELD_NET_AMOUNT,
                    $validationGroups,
                    $moneyCurrencyOptions
                );
                $this->configureMoneyInputs(
                    $event->getForm(),
                    static::FIELD_GROSS_AMOUNT,
                    $validationGroups,
                    $moneyCurrencyOptions
                );
            }
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $fieldName
     * @param string $validationGroups
     * @param array $moneyCurrencyOptions
     *
     * @return void
     */
    protected function configureMoneyInputs(
        FormInterface $form,
        $fieldName,
        $validationGroups,
        array $moneyCurrencyOptions
    ) {
        $field = $form->get($fieldName);
        $options = $field->getConfig()->getOptions();
        $form->remove($fieldName);

        $this->addFieldAmount($form, $fieldName, $validationGroups, array_merge($options, $moneyCurrencyOptions));
    }

    /**
     * Configures the options for this type.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver The resolver for the options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_VALIDATION_GROUPS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     * @param string $fieldName
     * @param string $validationGroups
     * @param array $options
     *
     * @return $this
     */
    protected function addFieldAmount($builder, $fieldName, $validationGroups, array $options = [])
    {
        $defaultOptions = [
            'attr' => [
                'class' => 'input-group',
            ],
            'constraints' => [
                new LessThanOrEqual([
                    'value' => static::MAX_MONEY_INT,
                    'groups' => $validationGroups,
                ]),
                new GreaterThanOrEqual([
                    'value' => static::MIN_MONEY_INT,
                    'groups' => $validationGroups,
                ]),
                new Regex([
                    'pattern' => static::REGULAR_EXPRESSION_MONEY_VALUE,
                    'groups' => $validationGroups,
                ]),
            ],
        ];

        $builder->add($fieldName, SimpleMoneyType::class, array_merge($defaultOptions, $options));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldFkCurrency(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_CURRENCY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFieldFkStore(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_STORE, HiddenType::class);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $viewData */
        $viewData = $form->getViewData();
        if (!method_exists($viewData, 'getCurrency')) {
            throw new Exception(sprintf(
                'Transfer object "%s" missing "%s" method which should provide currency transfer for current formType.',
                get_class($viewData),
                'getCurrency'
            ));
        }

        $storeName = '';
        if ($viewData->getFkStore()) {
            $storeTransfer = $this->createMoneyDataProvider()->getStoreById($viewData->getFkStore());
            $storeName = $storeTransfer->getName();
        }

        $view->vars['currency_symbol'] = $viewData->getCurrency()->getSymbol();
        $view->vars['store_name'] = $storeName;
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\DataProvider\MoneyDataProvider
     */
    protected function createMoneyDataProvider()
    {
        return $this->getFactory()->createMoneyDataProvider();
    }
}
