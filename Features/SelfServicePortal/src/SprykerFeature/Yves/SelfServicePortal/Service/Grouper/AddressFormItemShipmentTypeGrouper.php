<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Grouper;

use Symfony\Component\Form\FormView;

class AddressFormItemShipmentTypeGrouper extends AbstractShipmentTypeGrouper implements AddressFormItemShipmentTypeGrouperInterface
{
    /**
     * @var string
     */
    protected const FORM_PROPERTY_DATA = 'data';

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return array<string, array<string, list<\Symfony\Component\Form\FormView>>>
     */
    public function groupItemsByShipmentType(FormView $checkoutAddressForm): array
    {
        $shipmentTypeGroups = [];

        foreach ($checkoutAddressForm as $checkoutAddressFormItem) {
            $itemTransfer = $checkoutAddressFormItem->vars[static::FORM_PROPERTY_DATA];

            $shipmentTypeKey = $itemTransfer->getShipmentType()?->getKey() ?? $this->SelfServicePortalConfig::SHIPMENT_TYPE_DELIVERY;
            if (!isset($shipmentTypeGroups[$shipmentTypeKey])) {
                $shipmentTypeGroups[$shipmentTypeKey] = $this->createShipmentTypeGroup($shipmentTypeKey);
            }
            $shipmentTypeGroups[$shipmentTypeKey][static::SHIPMENT_TYPE_GROUP_ITEMS][] = $checkoutAddressFormItem;
        }

        /** @var array<string, array<string, mixed>> $shipmentTypeGroups */
        return $this->shipmentTypeGroupSorter->sortShipmentTypeGroups($shipmentTypeGroups);
    }
}
