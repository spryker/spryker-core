<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class CustomersListType extends AbstractType
{
    public const TYPE_NAME = 'customers';

    public const FIELD_CUSTOMER = 'id_customer';

    public const OPTION_CUSTOMER_ARRAY = 'option-category-array';
    public const OPTION_VALUE = 'option-value';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCustomerField(
            $builder,
            $options[static::OPTION_CUSTOMER_ARRAY],
            $options[static::OPTION_VALUE]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_CUSTOMER_ARRAY)
            ->setDefault(static::OPTION_VALUE, null);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $customerList
     * @param string|null $value
     *
     * @return $this
     */
    protected function addCustomerField(FormBuilderInterface $builder, array $customerList, string $value = null)
    {
        $builder->add(static::FIELD_CUSTOMER, Select2ComboBoxType::class, [
            'property_path' => static::FIELD_CUSTOMER,
            'label' => 'Select Customer',
            'choices' => array_flip($customerList),
            'choices_as_values' => true,
            'multiple' => false,
            'required' => true,
            'data' => $value,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return static::TYPE_NAME;
    }
}
