<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Form;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Form\Type\AutosuggestType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Shipment\Communication\ShipmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 */
class CarrierForm extends AbstractType
{
    const FIELD_NAME_GLOSSARY_FIELD = 'glossaryKeyName';
    const FIELD_NAME_FIELD = 'name';
    const FIELD_IS_ACTIVE_FIELD = 'isActive';
    const FIELD_ID_CARRIER = 'id_carrier';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'carrier';
    }

    /**
     * @deprecated Use `getBlockPrefix()` instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addNameField($builder)
            ->addGlossaryKeyField($builder)
            ->addIsActiveField($builder)
            ->addIdCarrierField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME_FIELD, TextType::class, [
            'label' => 'Name',
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
    protected function addGlossaryKeyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_NAME_GLOSSARY_FIELD, AutosuggestType::class, [
            'label' => 'Name glossary key',
            'url' => '/glossary/ajax/keys',
            'constraints' => [
                new NotBlank(),
                new Callback([
                    'callback' => [$this, 'uniqueCarrierGlossaryKeyNameCheck'],
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
        $builder->add(self::FIELD_IS_ACTIVE_FIELD, CheckboxType::class, [
            'label' => 'Enabled?',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCarrierField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_CARRIER, HiddenType::class);

        return $this;
    }

    /**
     * @param string $carrierName
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function uniqueCarrierNameCheck($carrierName, ExecutionContextInterface $context)
    {
        $formData = $context->getRoot()->getData();
        $idCarrier = isset($formData[static::FIELD_ID_CARRIER]) ? $formData[static::FIELD_ID_CARRIER] : null;

        if ($this->hasExistingCarrierName($carrierName, $idCarrier)) {
            $context->addViolation('Carrier with the same name already exists.');
        }
    }

    /**
     * @param string $glossaryKeyName
     * @param \Symfony\Component\Validator\Context\ExecutionContextInterface $context
     *
     * @return void
     */
    public function uniqueCarrierGlossaryKeyNameCheck(string $glossaryKeyName, ExecutionContextInterface $context): void
    {
        $formData = $context->getRoot()->getData();
        $idCarrier = $formData[static::FIELD_ID_CARRIER] ?: null;

        if ($this->hasExistingCarrierGlossaryKeyName($glossaryKeyName, $idCarrier)) {
            $context->addViolation('Carrier with the same glossary key already exists.');
        }
    }

    /**
     * @param string $carrierName
     * @param int|null $idCarrier
     *
     * @return bool
     */
    protected function hasExistingCarrierName($carrierName, $idCarrier = null)
    {
        $count = $this->getQueryContainer()
            ->queryUniqueCarrierName($carrierName, $idCarrier)
            ->count();

        return $count > 0;
    }

    /**
     * @param string $glossaryKeyName
     * @param int|null $idCarrier
     *
     * @return bool
     */
    protected function hasExistingCarrierGlossaryKeyName(string $glossaryKeyName, ?int $idCarrier = null): bool
    {
        $shipmentCarrierQuery = $this->getQueryContainer()
            ->queryCarriers()
            ->filterByGlossaryKeyName($glossaryKeyName);

        if ($idCarrier !== null) {
            $shipmentCarrierQuery->filterByIdShipmentCarrier($idCarrier, Criteria::NOT_EQUAL);
        }

        return $shipmentCarrierQuery->count() > 0;
    }
}
