<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Shipment;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentMethodFormType extends AbstractType
{
    public const FIELD_ID_SHIPMENT_METHOD = 'idShipmentMethod';
    public const OPTION_SHIPMENT_METHOD_CHOICES = 'method_choices';
    public const OPTION_ID_SHIPMENT_METHOD = 'idShipmentMethod';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(static::OPTION_SHIPMENT_METHOD_CHOICES)
            ->setRequired(static::FIELD_ID_SHIPMENT_METHOD)
            ->setDefaults([
                'data_class' => ShipmentMethodTransfer::class,
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
        $this->addIdShipmentMethodField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addIdShipmentMethodField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(static::FIELD_ID_SHIPMENT_METHOD, ChoiceType::class, [
            'label' => false,
            'placeholder' => 'Select one',
            'choices' => array_flip($options[static::OPTION_SHIPMENT_METHOD_CHOICES]),
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        $builder->get(static::FIELD_ID_SHIPMENT_METHOD)
            ->addModelTransformer($this->getFactory()->createStringToNumberTransformer());

        return $this;
    }
}
