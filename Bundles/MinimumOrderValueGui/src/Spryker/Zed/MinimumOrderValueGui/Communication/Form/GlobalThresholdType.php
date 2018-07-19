<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication\Form;

use Generated\Shared\Transfer\GlobalThresholdTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\Communication\MinimumOrderValueGuiCommunicationFactory getFactory()
 */
class GlobalThresholdType extends AbstractType
{
    public const TYPE_NAME = 'global-threshold';

    public const FIELD_STORE = 'storeCurrency';
    public const FIELD_HARD_VALUE = 'hardValue';

    public const OPTION_STORES_ARRAY = 'option-stores-array';

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
        $this->addStoreField($builder, $options);
        $this->addHardValueField($builder, $options);

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
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addStoreField(FormBuilderInterface $builder, array $options): self
    {
        $storesList = $options[static::OPTION_STORES_ARRAY];

        $builder->add(static::FIELD_STORE, Select2ComboBoxType::class, [
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
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::TYPE_NAME;
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
