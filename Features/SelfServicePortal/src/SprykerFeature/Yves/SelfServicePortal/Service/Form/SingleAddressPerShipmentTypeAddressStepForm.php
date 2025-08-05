<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SingleAddressPerShipmentTypeAddressStepForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE = 'isSingleAddressPerShipmentType';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SINGLE_ADDRESS_PER_SHIPMENT_TYPE = 'customer.address.single_address_per_shipment_type';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addSingleAddressPerShipmentTypeSubForm($builder)
            ->addPreSubmitListener($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSingleAddressPerShipmentTypeSubForm(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $this->addSingleAddressPerShipmentTypeField($event);
        });

        return $this;
    }

    protected function addSingleAddressPerShipmentTypeField(FormEvent $event): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $event->getData();

        if (!$this->getFactory()->createAddressFormChecker()->isApplicableForSingleAddressPerShipmentType($itemTransfer)) {
            return;
        }

        $event->getForm()->add(static::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE, CheckboxType::class, [
            'property_path' => static::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE,
            'label' => static::GLOSSARY_KEY_SINGLE_ADDRESS_PER_SHIPMENT_TYPE,
            'required' => false,
            'mapped' => true,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPreSubmitListener(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $this->getFactory()->createSingleAddressPerShipmentTypePreSubmitHandler()->handlePreSubmit($event);
        });

        return $this;
    }
}
