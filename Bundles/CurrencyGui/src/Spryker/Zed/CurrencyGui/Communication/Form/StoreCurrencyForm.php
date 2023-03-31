<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Form;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\CurrencyGui\Communication\CurrencyGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CurrencyGui\CurrencyGuiConfig getConfig()
 */
class StoreCurrencyForm extends AbstractType
{
    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'defaultCurrencyIsoCode';

    /**
     * @var string
     */
    protected const CURRENCIES_TO_BE_ASSIGNED = 'currencyCodesToBeAssigned';

    /**
     * @var string
     */
    protected const CURRENCIES_TO_BE_DE_ASSIGNED = 'currencyCodesToBeDeAssigned';

    /**
     * @phpstan-var non-empty-string
     *
     * @var string
     */
    protected const CURRENCY_CODES_SEPARATOR = ',';

    /**
     * @var string
     */
    public const CURRENCY_OPTIONS = 'CURRENCY_OPTIONS';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addDefaultCurrencyFied($builder, $options[static::CURRENCY_OPTIONS]);
        $this->addCurrenciesToBeAssignedField($builder);
        $this->addCurrenciesToBeDeAssignedField($builder);

        $this->addPreSubmitEventListener($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param array<string> $choices
     *
     * @return $this
     */
    protected function addDefaultCurrencyFied(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::DEFAULT_CURRENCY, Select2ComboBoxType::class, [
            'multiple' => false,
            'choices' => $choices,
            'constraints' => $this->getDefaultCurrencyFieldConstraints(),
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getDefaultCurrencyFieldConstraints(): array
    {
        return [
            new NotBlank(),
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return void
     */
    protected function addPreSubmitEventListener(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $formEvent): void {
            $this->executePreSubmitHandler($formEvent);
        });
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $formEvent
     *
     * @return void
     */
    protected function executePreSubmitHandler(FormEvent $formEvent): void
    {
        $eventData = $formEvent->getData();
        $formData = $formEvent->getForm()->getData();

        $currencyCodesToBeAssigned = explode(static::CURRENCY_CODES_SEPARATOR, $eventData[static::CURRENCIES_TO_BE_ASSIGNED]);
        $currencyCodesToBeDeAssigned = explode(static::CURRENCY_CODES_SEPARATOR, $eventData[static::CURRENCIES_TO_BE_DE_ASSIGNED]);

        $newCurrencyCodes = array_merge(
            array_diff($formEvent->getForm()->getData()[StoreTransfer::AVAILABLE_CURRENCY_ISO_CODES], $currencyCodesToBeDeAssigned),
            $currencyCodesToBeAssigned,
        );

        $formData[StoreTransfer::AVAILABLE_CURRENCY_ISO_CODES] = $newCurrencyCodes;

        $formEvent->getForm()->setData($formData);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return $this
     */
    protected function addCurrenciesToBeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::CURRENCIES_TO_BE_ASSIGNED, HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'id' => static::CURRENCIES_TO_BE_ASSIGNED,
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     *
     * @return $this
     */
    protected function addCurrenciesToBeDeAssignedField(FormBuilderInterface $builder)
    {
        $builder
            ->add(static::CURRENCIES_TO_BE_DE_ASSIGNED, HiddenType::class, [
                'mapped' => false,
                'attr' => [
                    'id' => static::CURRENCIES_TO_BE_DE_ASSIGNED,
                ],
            ]);

        return $this;
    }
}
