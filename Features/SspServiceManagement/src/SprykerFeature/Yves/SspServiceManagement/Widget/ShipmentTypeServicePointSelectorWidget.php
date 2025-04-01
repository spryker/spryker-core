<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementFactory getFactory()
 */
class ShipmentTypeServicePointSelectorWidget extends AbstractWidget
{
    /**
     * @var string
     */
    public const DEFAULT_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT = 'item_metadata_scheduled_at';

    /**
     * @var string
     */
    public const DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID = 'shipment_type_uuid';

    /**
     * @var string
     */
    protected const NAME = 'ShipmentTypeServicePointSelectorWidget';

    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_TYPES = 'shipmentTypes';

    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_TYPE_OPTIONS = 'shipmentTypeOptions';

    /**
     * @var string
     */
    protected const PARAMETER_IS_DISABLED = 'isDisabled';

    /**
     * @var string
     */
    protected const PARAMETER_HAS_ONLY_SERVICE_SHIPMENT_TYPE = 'hasOnlyServiceShipmentType';

    /**
     * @var string
     */
    protected const PARAMETER_HAS_ONLY_DELIVERY_SHIPMENT_TYPE = 'hasOnlyDeliveryShipmentType';

    /**
     * @var string
     */
    protected const PARAMETER_FORM_FIELD_SHIPMENT_TYPE_UUID = 'formFieldShipmentTypeUuid';

    /**
     * @var string
     */
    protected const PARAMETER_FORM_FIELD_SERVICE_POINT_UUID = 'formFieldServicePointUuid';

    /**
     * @var string
     */
    protected const PARAMETER_FORM_FIELD_PRODUCT_OFFER_REFERENCE = 'formFieldProductOfferReference';

    /**
     * @var string
     */
    protected const PARAMETER_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT = 'formFieldItemMetadataScheduledAt';

    /**
     * @var string
     */
    protected const PARAMETER_IS_SERVICE_DATE_TIME_ENABLED = 'isServiceDateTimeEnabled';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    /**
     * @var string
     */
    protected const DEFAULT_FORM_FIELD_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    protected const DEFAULT_FORM_FIELD_SERVICE_POINT_UUID = 'service_point_uuid';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isDisabled
     */
    public function __construct(
        ProductViewTransfer $productViewTransfer,
        bool $isDisabled = false
    ) {
        $currentStore = $this->getFactory()->getStoreClient()->getCurrentStore();

        $shipmentTypeStorageCollection = $this->getFactory()
            ->createShipmentTypeReader()
            ->getShipmentTypeStorageCollection(
                $productViewTransfer->getShipmentTypeUuids(),
                $currentStore->getNameOrFail(),
            );

        $shipmentTypes = $shipmentTypeStorageCollection->getShipmentTypeStorages();
        $hasShipmentTypes = $shipmentTypes->count() > 0;
        $shipmentTypeList = $shipmentTypes->getArrayCopy();

        $shipmentTypeChecker = $this->getFactory()->createShipmentTypeChecker();
        $hasOnlyServiceShipmentType = $hasShipmentTypes && $shipmentTypeChecker
            ->hasOnlyServiceShipmentType($shipmentTypeList);
        $hasOnlyDeliveryShipmentType = $hasShipmentTypes && $shipmentTypeChecker
            ->hasOnlyDeliveryShipmentType($shipmentTypeList);

        $this->addProductParameter($productViewTransfer);
        $this->addIsDisabledParameter($isDisabled);
        $this->addShipmentTypeOptionsParameter($shipmentTypeList);
        $this->addHasOnlyServiceShipmentTypeParameter($hasOnlyServiceShipmentType);
        $this->addHasOnlyDeliveryShipmentTypeParameter($hasOnlyDeliveryShipmentType);
        $this->addIsServiceDateTimeEnabledParameter((bool)$productViewTransfer->getIsServiceDateTimeEnabled());
        $this->addFormFieldShipmentTypeUuidParameter();
        $this->addFormFieldServicePointUuidParameter();
        $this->addFormFieldProductOfferReferenceParameter();
        $this->addFormFieldItemMetadataScheduledAtParameter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspServiceManagement/views/shipment-type-service-point-selector/shipment-type-service-point-selector.twig';
    }

    /**
     * @param bool $isDisabled
     *
     * @return void
     */
    protected function addIsDisabledParameter(bool $isDisabled): void
    {
        $this->addParameter(static::PARAMETER_IS_DISABLED, $isDisabled);
    }

    /**
     * @param bool $hasOnlyServiceShipmentType
     *
     * @return void
     */
    protected function addHasOnlyServiceShipmentTypeParameter(bool $hasOnlyServiceShipmentType): void
    {
        $this->addParameter(static::PARAMETER_HAS_ONLY_SERVICE_SHIPMENT_TYPE, $hasOnlyServiceShipmentType);
    }

    /**
     * @param bool $hasOnlyDeliveryShipmentType
     *
     * @return void
     */
    protected function addHasOnlyDeliveryShipmentTypeParameter(bool $hasOnlyDeliveryShipmentType): void
    {
        $this->addParameter(static::PARAMETER_HAS_ONLY_DELIVERY_SHIPMENT_TYPE, $hasOnlyDeliveryShipmentType);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return void
     */
    protected function addShipmentTypeOptionsParameter(array $shipmentTypeTransfers): void
    {
        $this->addParameter(
            static::PARAMETER_SHIPMENT_TYPE_OPTIONS,
            $this->getFactory()->createShipmentTypeOptionsProvider()->provideShipmentTypeOptions($shipmentTypeTransfers),
        );
    }

    /**
     * @param bool $isServiceDateTimeEnabled
     *
     * @return void
     */
    protected function addIsServiceDateTimeEnabledParameter(bool $isServiceDateTimeEnabled): void
    {
        $this->addParameter(static::PARAMETER_IS_SERVICE_DATE_TIME_ENABLED, $isServiceDateTimeEnabled);
    }

    /**
     * @return void
     */
    protected function addFormFieldShipmentTypeUuidParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_FORM_FIELD_SHIPMENT_TYPE_UUID,
            static::DEFAULT_FORM_FIELD_SHIPMENT_TYPE_UUID,
        );
    }

    /**
     * @return void
     */
    protected function addFormFieldServicePointUuidParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_FORM_FIELD_SERVICE_POINT_UUID,
            static::DEFAULT_FORM_FIELD_SERVICE_POINT_UUID,
        );
    }

    /**
     * @return void
     */
    protected function addFormFieldProductOfferReferenceParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_FORM_FIELD_PRODUCT_OFFER_REFERENCE,
            static::DEFAULT_FORM_FIELD_PRODUCT_OFFER_REFERENCE,
        );
    }

    /**
     * @return void
     */
    protected function addFormFieldItemMetadataScheduledAtParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT,
            static::DEFAULT_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }
}
