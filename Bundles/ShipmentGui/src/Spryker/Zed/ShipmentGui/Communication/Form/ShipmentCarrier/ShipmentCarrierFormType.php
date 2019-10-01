<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentCarrier;

use Generated\Shared\Transfer\ShipmentCarrierRequestTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentCarrierFormType extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_IS_ACTIVE = 'isActive';
    public const FIELD_CARRIER_ID = 'idShipmentCarrier';

    protected const LABEL_NAME = 'Name';
    protected const LABEL_IS_ACTIVE_FIELD = 'Enabled?';
    protected const MESSAGE_VIOLATION = 'Carrier with the same name already exists.';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShipmentCarrierTransfer::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'shipment_carrier';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addIsActiveField($builder)
            ->addIdShipmentCarrierField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => [$this, 'uniqueCarrierNameCheck'],
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsActiveField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ACTIVE, CheckboxType::class, [
            'label' => static::LABEL_IS_ACTIVE_FIELD,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdShipmentCarrierField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CARRIER_ID, HiddenType::class);

        return $this;
    }

    /**
     * @param string $carrierName
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function uniqueCarrierNameCheck(string $carrierName, ExecutionContextInterface $context): void
    {
        $shipmentCarrierRequestTransfer = $this->createShipmentCarrierTransfer($carrierName, $context);

        if ($this->getFactory()->getShipmentFacade()->findShipmentCarrier($shipmentCarrierRequestTransfer)) {
            $context->addViolation(static::MESSAGE_VIOLATION);
        }
    }

    /**
     * @param string $carrierName
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierRequestTransfer
     */
    protected function createShipmentCarrierTransfer(string $carrierName, ExecutionContextInterface $context): ShipmentCarrierRequestTransfer
    {
        $formData = $context->getRoot()->getData();
        $carrierId = isset($formData[static::FIELD_CARRIER_ID]) ? $formData[static::FIELD_CARRIER_ID] : null;

        return (new ShipmentCarrierRequestTransfer())
            ->setCarrierName($carrierName)
            ->setExcludedCarrierIds([$carrierId]);
    }
}
