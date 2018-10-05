<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Shipment;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class ShipmentType extends AbstractType
{
    public const TYPE_NAME = 'shipments';

    public const FIELD_SHIPMENT_METHOD = 'idShipmentMethod';

    public const OPTION_SHIPMENT_METHODS_ARRAY = 'option-shipment-methods-array';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addStoreField(
            $builder,
            $options[static::OPTION_SHIPMENT_METHODS_ARRAY]
        );
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired(static::OPTION_SHIPMENT_METHODS_ARRAY);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $shipmentMethods
     *
     * @return $this
     */
    protected function addStoreField(FormBuilderInterface $builder, array $shipmentMethods): self
    {
        $builder->add(static::FIELD_SHIPMENT_METHOD, Select2ComboBoxType::class, [
            'property_path' => QuoteTransfer::SHIPMENT . '.' . ShipmentTransfer::METHOD . '.' . ShipmentMethodTransfer::ID_SHIPMENT_METHOD,
            'label' => 'Selecting a shipment method',
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
    public function getBlockPrefix(): string
    {
        return static::TYPE_NAME;
    }
}
