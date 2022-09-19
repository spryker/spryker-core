<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MoneyGui\Communication\Form\Type;

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
 * @method \Spryker\Zed\MoneyGui\Communication\MoneyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MoneyGui\MoneyGuiConfig getConfig()
 */
class MoneyType extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NET_AMOUNT = 'net_amount';

    /**
     * @var string
     */
    protected const FIELD_GROSS_AMOUNT = 'gross_amount';

    /**
     * @var string
     */
    protected const FIELD_FK_CURRENCY = 'fk_currency';

    /**
     * @var string
     */
    protected const FIELD_FK_STORE = 'fk_store';

    /**
     * @var int
     */
    protected const MAX_MONEY_INT = 21474835;

    /**
     * @var int
     */
    protected const MIN_MONEY_INT = 0;

    /**
     * @var string
     */
    protected const OPTION_VALIDATION_GROUPS = 'validation_groups';

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const REGULAR_EXPRESSION_MONEY_VALUE = '/[0-9\.\,]+/';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $validationGroups = $options[static::OPTION_VALIDATION_GROUPS];
        $this->addAmountField($builder, static::FIELD_NET_AMOUNT, $validationGroups, [
                static::OPTION_LOCALE => $options[static::OPTION_LOCALE],
            ])
            ->addAmountField($builder, static::FIELD_GROSS_AMOUNT, $validationGroups, [
                static::OPTION_LOCALE => $options[static::OPTION_LOCALE],
            ])
            ->addFkCurrencyField($builder)
            ->addFkStoreField($builder);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($validationGroups): void {
                $moneyCurrencyOptions = $this->getFactory()
                    ->createMoneyTypeDataProvider()
                    ->getMoneyCurrencyOptions($event->getData());

                $this->configureMoneyInputs(
                    $event->getForm(),
                    static::FIELD_NET_AMOUNT,
                    $validationGroups,
                    $moneyCurrencyOptions,
                );
                $this->configureMoneyInputs(
                    $event->getForm(),
                    static::FIELD_GROSS_AMOUNT,
                    $validationGroups,
                    $moneyCurrencyOptions,
                );
            },
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_VALIDATION_GROUPS,
        ]);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
        $moneyValueTransfer = $form->getViewData();

        $storeName = '';
        if ($moneyValueTransfer->getFkStore()) {
            $storeTransfer = $this->getFactory()
                ->createMoneyTypeDataProvider()
                ->getStoreById($moneyValueTransfer->getFkStoreOrFail());
            $storeName = $storeTransfer->getName();
        }

        $view->vars['currency_symbol'] = $moneyValueTransfer->getCurrencyOrFail()->getSymbol();
        $view->vars['store_name'] = $storeName;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param string $fieldName
     * @param string $validationGroups
     * @param array<string, int> $moneyCurrencyOptions
     *
     * @return void
     */
    protected function configureMoneyInputs(
        FormInterface $form,
        $fieldName,
        $validationGroups,
        array $moneyCurrencyOptions
    ): void {
        $field = $form->get($fieldName);
        $options = $field->getConfig()->getOptions();
        $form->remove($fieldName);

        $this->addAmountField($form, $fieldName, $validationGroups, array_merge($options, $moneyCurrencyOptions));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     * @param string $fieldName
     * @param string $validationGroups
     * @param array<string> $options
     *
     * @return $this
     */
    protected function addAmountField($builder, $fieldName, $validationGroups, array $options = [])
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
    protected function addFkCurrencyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_CURRENCY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkStoreField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FK_STORE, HiddenType::class);

        return $this;
    }
}
