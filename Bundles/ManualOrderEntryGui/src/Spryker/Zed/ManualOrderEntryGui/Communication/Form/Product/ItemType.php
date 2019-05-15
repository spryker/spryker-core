<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ItemType extends AbstractType
{
    public const FIELD_SKU = 'sku';
    public const FIELD_QUANTITY = 'quantity';
    public const FIELD_UNIT_GROSS_PRICE = 'unitGrossPrice';
    public const FIELD_FORCED_UNIT_GROSS_PRICE = 'forcedUnitGrossPrice';

    public const OPTION_ISO_CODE = 'isoCode';

    protected const ERROR_MESSAGE_QUANTITY = 'Invalid Quantity.';
    protected const ERROR_MESSAGE_PRICE = 'Invalid Price.';
    protected const PATTERN_MONEY = '/^\d*\.?\d{0,2}$/';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(static::OPTION_ISO_CODE);

        $resolver->setDefaults([
            'constraints' => [
                $this->getFactory()->createSkuExistsConstraint(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addSkuField($builder, $options)
            ->addQuantityField($builder, $options)
            ->addUnitGrossPriceField($builder, $options)
            ->addForcedUnitGrossPriceField($builder, $options);

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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SKU, TextType::class, [
            'label' => 'SKU',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addUnitGrossPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_UNIT_GROSS_PRICE, TextType::class, [
            'label' => 'Unit Gross Price',
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
    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUANTITY, TextType::class, [
            'label' => 'Quantity',
            'required' => false,
            'constraints' => [
                $this->createNumberConstraint($options),
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
    protected function addForcedUnitGrossPriceField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FORCED_UNIT_GROSS_PRICE, HiddenType::class, [
            'data' => 1,
        ]);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createNumberConstraint(array $options): Regex
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => '/^\d*$/',
            'message' => static::ERROR_MESSAGE_QUANTITY,
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
            'message' => static::ERROR_MESSAGE_PRICE,
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

        if ($data instanceof ItemTransfer) {
            /** @var int $moneyFloat */
            $moneyFloat = $moneyFacade->convertIntegerToDecimal((int)$data->getUnitGrossPrice());
            $data->setUnitGrossPrice($moneyFloat);

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

        if ($data instanceof ItemTransfer) {
            $moneyFloat = $moneyFacade->convertDecimalToInteger((float)$data->getUnitGrossPrice());
            $data->setUnitGrossPrice($moneyFloat);

            $event->setData($data);
        }
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'product';
    }
}
