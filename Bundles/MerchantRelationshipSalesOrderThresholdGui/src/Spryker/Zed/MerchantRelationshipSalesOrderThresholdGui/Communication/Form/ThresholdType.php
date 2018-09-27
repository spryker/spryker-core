<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\MerchantRelationshipSalesOrderThresholdGuiCommunicationFactory getFactory()
 */
class ThresholdType extends AbstractType
{
    public const TYPE_NAME = 'threshold';

    public const PREFIX_HARD = 'hard';
    public const PREFIX_SOFT = 'soft';

    public const FIELD_STORE_CURRENCY = 'storeCurrency';
    public const FIELD_CURRENCY = 'currency';
    public const FIELD_ID_MERCHANT_RELATIONSHIP = 'idMerchantRelationship';
    public const FIELD_ID_MERCHANT_RELATIONSHIP_THRESHOLD_HARD = 'idMerchantRelationshipThresholdHard';
    public const FIELD_HARD_THRESHOLD = 'hardThreshold';

    public const FIELD_ID_MERCHANT_RELATIONSHIP_THRESHOLD_SOFT = 'idMerchantRelationshipThresholdSoft';
    public const FIELD_SOFT_STRATEGY = 'softStrategy';
    public const FIELD_SOFT_THRESHOLD = 'softThreshold';
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
        $this->addIdMerchantRelationshipField($builder);

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
    public static function getLocalizedFormName(string $prefix, string $localeCode): string
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
     *
     * @return $this
     */
    protected function addIdMerchantRelationshipField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_MERCHANT_RELATIONSHIP, HiddenType::class, [
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
    protected function addStoreCurrencyField(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_STORE_CURRENCY, Select2ComboBoxType::class, [
            'label' => 'Store and Currency',
            'choices' => $options[static::OPTION_STORES_ARRAY],
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

        $builder->add(static::FIELD_HARD_THRESHOLD, MoneyType::class, [
            'label' => 'Enter minimum order value',
            'currency' => $options['data'][static::FIELD_CURRENCY],
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
            'required' => false,
            'expanded' => true,
            'choices' => $options[static::OPTION_SOFT_TYPES_ARRAY],
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
        $builder->add(static::FIELD_SOFT_THRESHOLD, MoneyType::class, [
            'label' => 'Enter minimum order value',
            'currency' => $options['data'][static::FIELD_CURRENCY],
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
        $builder->add(static::FIELD_SOFT_FIXED_FEE, MoneyType::class, [
            'label' => 'Enter fixed fee',
            'currency' => $options['data'][static::FIELD_CURRENCY],
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
        $builder->add(static::FIELD_SOFT_FLEXIBLE_FEE, PercentType::class, [
            'label' => 'Enter flexible fee',
            'type' => 'integer',
            'required' => false,
            'constraints' => [
                $this->createRangeConstraint($options, 0, 100),
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
    protected function addLocalizedForms(FormBuilderInterface $builder, string $localizedFormPrefix): self
    {
        $localeCollection = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        foreach ($localeCollection as $localeTransfer) {
            $name = static::getLocalizedFormName($localizedFormPrefix, $localeTransfer->getLocaleName());
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
    protected function addLocalizedForm(FormBuilderInterface $builder, string $name, array $options = []): self
    {
        $builder
            ->add($name, LocalizedForm::class, [
                'label' => false,
            ]);

        return $this;
    }

    /**
     * @param array $options
     * @param int $min
     * @param int $max
     *
     * @return \Symfony\Component\Validator\Constraints\Range
     */
    protected function createRangeConstraint(array $options, int $min, int $max): Range
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Range([
            'min' => $min,
            'max' => $max,
            'groups' => $validationGroup,
        ]);
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
        $data = $event->getData();

        if (is_array($data)) {
            foreach ([
                        static::FIELD_HARD_THRESHOLD,
                        static::FIELD_SOFT_THRESHOLD,
                        static::FIELD_SOFT_FIXED_FEE,
                     ] as $fieldName) {
                $data = $this->convertMoneyValue(
                    $data,
                    $fieldName,
                    'intval',
                    [$this->getFactory()->getMoneyFacade(), 'convertIntegerToDecimal']
                );
            }

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
        $data = $event->getData();

        if (is_array($data)) {
            foreach ([
                        static::FIELD_HARD_THRESHOLD,
                        static::FIELD_SOFT_THRESHOLD,
                        static::FIELD_SOFT_FIXED_FEE,
                     ] as $fieldName) {
                $data = $this->convertMoneyValue(
                    $data,
                    $fieldName,
                    'floatval',
                    [$this->getFactory()->getMoneyFacade(), 'convertDecimalToInteger']
                );
            }

            $event->setData($data);
        }
    }

    /**
     * @param array $data
     * @param string $fieldName
     * @param callable $typeConvertFunction
     * @param callable $moneyConvertFunction
     *
     * @return array
     */
    protected function convertMoneyValue(array $data, string $fieldName, callable $typeConvertFunction, callable $moneyConvertFunction): array
    {
        if (!isset($data[$fieldName])) {
            $data[$fieldName] = 0;

            return $data;
        }
        $data[$fieldName] = $moneyConvertFunction($typeConvertFunction($data[$fieldName]));

        return $data;
    }
}
