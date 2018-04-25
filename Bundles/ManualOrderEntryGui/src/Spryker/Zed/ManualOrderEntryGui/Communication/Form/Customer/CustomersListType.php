<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
    public const FIELD_CUSTOMER = 'idCustomer';
    public const OPTION_CUSTOMER_ARRAY = 'option-category-array';

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
            $options[static::OPTION_CUSTOMER_ARRAY]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_CUSTOMER_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $customerList
     * @param string|null $value
     *
     * @return $this
     */
    protected function addCustomerField(FormBuilderInterface $builder, array $customerList, ?string $value = null)
    {
        $builder->add(static::FIELD_CUSTOMER, Select2ComboBoxType::class, [
            'property_path' => QuoteTransfer::CUSTOMER . '.' . CustomerTransfer::ID_CUSTOMER,
            'label' => 'Select Customer',
            'choices' => array_flip($customerList),
            'choices_as_values' => true,
            'multiple' => false,
            'required' => true,
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
