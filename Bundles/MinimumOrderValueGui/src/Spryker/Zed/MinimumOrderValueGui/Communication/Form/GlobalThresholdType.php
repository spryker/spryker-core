<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form;

use Generated\Shared\Transfer\GlobalThresholdTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory getFactory()
 */
class GlobalThresholdType extends AbstractType
{
    public const TYPE_NAME = 'global-threshold';

    public const PREFIX_HARD = 'hard';
    public const PREFIX_SOFT = 'soft';

    public const FIELD_STORE_CURRENCY = 'storeCurrency';
    public const FIELD_HARD_VALUE = 'hardValue';

    public const FIELD_SOFT_STRATEGY = 'softStrategy';
    public const FIELD_SOFT_VALUE = 'softValue';
    public const FIELD_SOFT_FIXED_FEE = 'softFixedFee';
    public const FIELD_SOFT_FLEXIBLE_FEE = 'softFlexibleFee';

    public const OPTION_STORES_ARRAY = 'option-stores-array';
    public const OPTION_SOFT_TYPES_ARRAY = 'option-soft-types-array';
    public const VALIDATION_GROUP_GENERAL = 'validation_group_general';

    protected const PATTERN_MONEY = '/^\d*\.?\d{0,2}$/';
    protected const ERROR_MESSAGE_VALUE = 'Invalid Value.';


    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreCurrencyField($builder, $options);

        $this->addHardValueField($builder, $options);
        $this->addLocalizedForms($builder, static::PREFIX_HARD);

        $this->addSoftStrategyField($builder, $options);
        $this->addSoftValueField($builder, $options);
        $this->addSoftFixedFeeField($builder, $options);
        $this->addSoftFlexibleFeeField($builder, $options);
        $this->addLocalizedForms($builder, static::PREFIX_SOFT);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                $this->convertIntToMoney($event, $options);
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($options) {
                $this->convertMoneyToInt($event, $options);
            }
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_STORES_ARRAY);
        $resolver->setRequired(static::OPTION_SOFT_TYPES_ARRAY);
    }

    /**
     * @param string $prefix
     * @param string $localeCode
     *
     * @return string
     */
    public static function getLocalizedFormName($prefix, $localeCode): string
    {
        return $prefix . '_' . $localeCode;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStoreCurrencyField(FormBuilderInterface $builder, array $options): self
    {
        $storesList = $options[static::OPTION_STORES_ARRAY];

        $builder->add(static::FIELD_STORE_CURRENCY, Select2ComboBoxType::class, [
            'label' => 'Store and Currency',
            'choices' => array_flip($storesList),
            'choices_as_values' => true,
            'multiple' => false,
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addHardValueField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_HARD_VALUE, TextType::class, [
            'label' => 'Enter minimum order value',
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftStrategyField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_SOFT_STRATEGY, ChoiceType::class, [
            'label' => false,
            'required' => true,
            'expanded' => true,
            'choices' => array_flip($options[static::OPTION_SOFT_TYPES_ARRAY]),
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftValueField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_SOFT_VALUE, TextType::class, [
            'label' => 'Enter minimum order value',
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftFixedFeeField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_SOFT_FIXED_FEE, TextType::class, [
            'label' => 'Enter fixed fee',
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSoftFlexibleFeeField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_SOFT_FLEXIBLE_FEE, TextType::class, [
            'label' => 'Enter flexible fee',
            'required' => false,
            'constraints' => [
                $this->createMoneyConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $localizedFormPrefix
     *
     * @return $this
     */
    protected function addLocalizedForms(FormBuilderInterface $builder, $localizedFormPrefix): self
    {
        $localeCollection = $this->getFactory()
            ->createLocaleProvider()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $name = self::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
            $this->addLocalizedForm($builder, $name);
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
    protected function addLocalizedForm(FormBuilderInterface $builder, $name, array $options = []): self
    {
        $builder
            ->add($name, LocalizedForm::class, [
                'label' => false,
//                'constraints' => [new Callback([
//                    'callback' => function ($dataToValidate, ExecutionContextInterface $context) {
//                        $selectedAttributes = array_filter(array_values($dataToValidate));
//                        if (empty($selectedAttributes) && !array_key_exists($context->getGroup(), LocalizedForm::$errorFieldsDisplayed)) {
//                            $context->addViolation('Please enter at least Sku and Name of the product in every locale under General');
//                            LocalizedForm::$errorFieldsDisplayed[$context->getGroup()] = true;
//                        }
//                    },
//                    'groups' => [self::VALIDATION_GROUP_GENERAL],
//                ])],
            ]);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createMoneyConstraint(array $options): Regex
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => static::PATTERN_MONEY,
            'message' => static::ERROR_MESSAGE_VALUE,
            'groups' => $validationGroup,
        ]);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getValidationGroup(array $options): string
    {
        $validationGroup = Constraint::DEFAULT_GROUP;

        if (!empty($options['validation_group'])) {
            $validationGroup = $options['validation_group'];
        }

        return $validationGroup;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array $options
     *
     * @return void
     */
    protected function convertIntToMoney(FormEvent $event, array $options): void
    {
        $moneyFacade = $this->getFactory()->getMoneyFacade();
        $data = $event->getData();

        if ($data instanceof GlobalThresholdTransfer) {
            $moneyFloat = $moneyFacade->convertIntegerToDecimal((int)$data->getHardValue());
            $data->setHardValue($moneyFloat);

            $event->setData($data);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array $options
     *
     * @return void
     */
    protected function convertMoneyToInt(FormEvent $event, array $options): void
    {
        $moneyFacade = $this->getFactory()->getMoneyFacade();
        $data = $event->getData();

        if ($data instanceof GlobalThresholdTransfer) {
            $moneyInt = $moneyFacade->convertDecimalToInteger((float)$data->getHardValue());
            $data->setHardValue($moneyInt);

            $event->setData($data);
        }
    }
}
