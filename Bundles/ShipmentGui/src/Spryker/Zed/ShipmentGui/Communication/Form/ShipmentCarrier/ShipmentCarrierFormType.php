<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentCarrier;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Communication\ShipmentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\ShipmentGuiConfig getConfig()
 */
class ShipmentCarrierFormType extends AbstractType
{
    public const FIELD_NAME_FIELD = 'name';
    public const FIELD_IS_ACTIVE_FIELD = 'isActive';
    public const FIELD_ID_CARRIER = 'id_carrier';

    protected const LABEL_NAME = 'Name';
    protected const LABEL_IS_ACTIVE_FIELD = 'Enabled?';
    protected const MESSAGE_VIOLATION = 'Carrier with the same name already exists.';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'carrier';
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
        $builder->add(static::FIELD_NAME_FIELD, TextType::class, [
            'label' => static::LABEL_NAME,
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => [$this, 'uniqueShipmentCarrierNameCheck'],
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
        $builder->add(static::FIELD_IS_ACTIVE_FIELD, CheckboxType::class, [
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
        $builder->add(static::FIELD_ID_CARRIER, HiddenType::class);

        return $this;
    }

    /**
     * @param string $carrierName
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function uniqueShipmentCarrierNameCheck($carrierName, ExecutionContextInterface $context): void
    {
        $formData = $context->getRoot()->getData();
        $idCarrier = $formData[static::FIELD_ID_CARRIER] ?? null;

        if ($this->getFactory()->getShipmentFacade()->hasCarrierName($carrierName, $idCarrier)) {
            $context->addViolation(static::MESSAGE_VIOLATION);
        }
    }
}
