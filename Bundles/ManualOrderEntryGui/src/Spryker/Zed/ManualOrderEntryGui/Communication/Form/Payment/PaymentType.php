<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Payment;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class PaymentType extends AbstractType
{

    const FIELD_SHIPMENT_METHOD = 'id_shipment_method';

    const OPTION_PAYMENT_METHODS_ARRAY = 'option-payment-methods-array';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addStoreField(
            $builder,
            $options[static::OPTION_PAYMENT_METHODS_ARRAY]
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
            ->setRequired(static::OPTION_PAYMENT_METHODS_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $shipmentMethods
     *
     * @return $this
     */
    protected function addStoreField(FormBuilderInterface $builder, array $shipmentMethods)
    {
        $builder->add(static::FIELD_SHIPMENT_METHOD, Select2ComboBoxType::class, [
            'property_path' => static::FIELD_SHIPMENT_METHOD,
            'label' => 'Payment Method',
            'choices' => array_flip($shipmentMethods),
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
        return 'shipments';
    }

}
