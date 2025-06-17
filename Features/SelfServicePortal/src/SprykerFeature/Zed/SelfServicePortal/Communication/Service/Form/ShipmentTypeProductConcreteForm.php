<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ShipmentTypeProductConcreteForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_VALUES_SHIPMENT_TYPES = 'OPTION_VALUES_SHIPMENT_TYPES';

    /**
     * @var string
     */
    protected const LABEL_SHIPMENT_TYPES = 'Allowed shipment types';

    /**
     * @uses \Generated\Shared\Transfer\ProductConcreteTransfer::SHIPMENT_TYPES
     *
     * @var string
     */
    public const FIELD_SHIPMENT_TYPES = 'shipmentTypes';

    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Service\Form\ProductConcreteFormEdit::FIELD_ID_PRODUCT_CONCRETE
     *
     * @var string
     */
    protected const FIELD_ID_PRODUCT_CONCRETE = 'id_product';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addShipmentTypeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_VALUES_SHIPMENT_TYPES);
        $resolver->setDefaults([static::OPTION_VALUES_SHIPMENT_TYPES => []]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function addShipmentTypeField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            static::FIELD_SHIPMENT_TYPES,
            Select2ComboBoxType::class,
            [
                'label' => static::LABEL_SHIPMENT_TYPES,
                'required' => false,
                'choices' => $options[static::OPTION_VALUES_SHIPMENT_TYPES],
                'multiple' => true,
            ],
        );

        $builder->get(static::FIELD_SHIPMENT_TYPES)
            ->addModelTransformer($this->createShipmentTypesModelTransformer($builder));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createShipmentTypesModelTransformer(FormBuilderInterface $builder): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($shipmentTypes) use ($builder) {
                $productConcreteFormData = $builder->getData();
                $defaultShipmentTypeTransfer = $this->getFactory()->createShipmentTypeReader()->findDefaultShipmentType();

                if (isset($productConcreteFormData[static::FIELD_ID_PRODUCT_CONCRETE]) && !$shipmentTypes) {
                    return $shipmentTypes;
                }

                if (!isset($productConcreteFormData[static::FIELD_ID_PRODUCT_CONCRETE]) && !$shipmentTypes && $defaultShipmentTypeTransfer) {
                    return [$defaultShipmentTypeTransfer->getIdShipmentTypeOrFail()];
                }

                $shipmentTypeIds = [];
                if ($shipmentTypes === null) {
                    return $shipmentTypeIds;
                }

                foreach ($shipmentTypes as $shipmentTypeTransfer) {
                    $shipmentTypeIds[] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
                }

                return $shipmentTypeIds;
            },
            function ($data) {
                $shipmentTypeTransfers = [];
                foreach ($data as $idShipmentType) {
                    $shipmentTypeTransfers[] = (new ShipmentTypeTransfer())
                        ->setIdShipmentType($idShipmentType);
                }

                return new ArrayObject($shipmentTypeTransfers);
            },
        );
    }
}
