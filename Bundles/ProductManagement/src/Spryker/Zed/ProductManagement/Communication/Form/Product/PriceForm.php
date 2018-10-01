<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Product;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 */
class PriceForm extends AbstractType
{
    public const FIELD_PRICE = 'price';
    public const FIELD_PRICES = 'prices';
    public const FIELD_TAX_RATE = 'tax_rate';

    public const OPTION_TAX_RATE_CHOICES = 'tax_rate_choices';
    public const OPTION_CURRENCY_ISO_CODE = 'currency_iso_code';
    public const OPTION_MONEY_FACADE = 'money-facade';
    public const OPTION_CURRENCY_FACADE = 'currency-facade';
    public const DEFAULT_SCALE = 2;
    public const MAX_PRICE_SIZE = 2147483647; // 32 bit integer

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_TAX_RATE_CHOICES,
        ]);

        $resolver->setDefaults([
            'required' => false,
            'constraints' => new Valid(),
            static::OPTION_CURRENCY_ISO_CODE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addPriceField($builder, $options)
            ->addPriceFieldCollection($builder, $options)
            ->addTaxRateField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPriceField(FormBuilderInterface $builder, array $options)
    {
        $currencyTransfer = $this->getFactory()->getCurrencyFacade()->getCurrent();

        $fieldOptions = [
            'label' => 'Price *',
            'required' => true,
            'divisor' => $this->getDivisor($currencyTransfer),
            'scale' => $this->getFractionDigits($currencyTransfer),
            'constraints' => [
                new NotBlank(),
                $this->createLessThanOrEqualConstraint($currencyTransfer),
                new GreaterThanOrEqual(0),
            ],
        ];

        if ($options[static::OPTION_CURRENCY_ISO_CODE] !== null) {
            $fieldOptions['currency'] = $options[static::OPTION_CURRENCY_ISO_CODE];
        }

        $builder->add(static::FIELD_PRICE, MoneyType::class, $fieldOptions);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addPriceFieldCollection(FormBuilderInterface $builder, array $options)
    {
        $currencyTransfer = $this->getFactory()->getCurrencyFacade()->getCurrent();

        $fieldOptions = [
            'label_format' => 'Price (%name%)',
            'divisor' => $this->getDivisor($currencyTransfer),
            'scale' => $this->getFractionDigits($currencyTransfer),
            'constraints' => [
                $this->createLessThanOrEqualConstraint($currencyTransfer),
                new GreaterThanOrEqual(0),
            ],
        ];

        if ($options[static::OPTION_CURRENCY_ISO_CODE] !== null) {
            $fieldOptions['currency'] = $options[static::OPTION_CURRENCY_ISO_CODE];
        }

        $builder->add(static::FIELD_PRICES, CollectionType::class, [
            'entry_type' => MoneyType::class,
            'entry_options' => $fieldOptions,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getDivisor(CurrencyTransfer $currencyTransfer)
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        $divisor = 1;
        if ($fractionDigits) {
            $divisor = pow(10, $fractionDigits);
        }

        return $divisor;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getFractionDigits(CurrencyTransfer $currencyTransfer)
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits !== null) {
            return $fractionDigits;
        }

        return static::DEFAULT_SCALE;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addTaxRateField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_TAX_RATE, Select2ComboBoxType::class, [
            'label' => 'Tax Set',
            'required' => true,
            'choices' => $options[static::OPTION_TAX_RATE_CHOICES],
            'placeholder' => '-',
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Symfony\Component\Validator\Constraints\LessThanOrEqual
     */
    protected function createLessThanOrEqualConstraint(CurrencyTransfer $currencyTransfer)
    {
        return new LessThanOrEqual([
            'value' => static::MAX_PRICE_SIZE,
            'message' => sprintf(
                'This value should be less than or equal to "%s".',
                $this->getMaxPriceValue($currencyTransfer)
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return string
     */
    protected function getMaxPriceValue(CurrencyTransfer $currencyTransfer)
    {
        return number_format(
            static::MAX_PRICE_SIZE / $this->getDivisor($currencyTransfer),
            $this->getDivisor($currencyTransfer),
            '.',
            ''
        );
    }
}
