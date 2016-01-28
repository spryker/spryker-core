<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerUpdateForm extends CustomerForm
{

    const FIELD_DEFAULT_BILLING_ADDRESS = 'default_billing_address';
    const FIELD_DEFAULT_SHIPPING_ADDRESS = 'default_shipping_address';

    const OPTION_ADDRESS_CHOICES = 'address_choices';

    /**
     * @param OptionsResolverInterface $resolver
     *
     * @return void
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setRequired(self::OPTION_ADDRESS_CHOICES);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $this
            ->addDefaultBillingAddressField($builder, $options[self::OPTION_ADDRESS_CHOICES])
            ->addDefaultShippingAddressField($builder, $options[self::OPTION_ADDRESS_CHOICES]);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return self
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_EMAIL, 'email', [
            'label' => 'Email',
            'constraints' => $this->createEmailConstraints(),
            'disabled' => 'disabled',
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return self
     */
    protected function addDefaultBillingAddressField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_DEFAULT_BILLING_ADDRESS, 'choice', [
            'label' => 'Billing Address',
            'placeholder' => 'Select one',
            'choices' => $choices,
        ]);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $choices
     *
     * @return self
     */
    protected function addDefaultShippingAddressField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(self::FIELD_DEFAULT_SHIPPING_ADDRESS, 'choice', [
            'label' => 'Shipping Address',
            'placeholder' => 'Select one',
            'choices' => $choices,
        ]);

        return $this;
    }

}
