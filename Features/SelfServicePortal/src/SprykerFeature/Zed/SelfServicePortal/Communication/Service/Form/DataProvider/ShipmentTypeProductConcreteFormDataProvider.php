<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ShipmentTypeProductConcreteForm;

class ShipmentTypeProductConcreteFormDataProvider
{
    /**
     * @param \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface $shipmentTypeFacade
     */
    public function __construct(protected ShipmentTypeFacadeInterface $shipmentTypeFacade)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $shipmentTypeChoices = $this->prepareShipmentTypeChoices();

        return [
            ShipmentTypeProductConcreteForm::OPTION_VALUES_SHIPMENT_TYPES => $shipmentTypeChoices,
        ];
    }

    /**
     * @return array<string, int>
     */
    protected function prepareShipmentTypeChoices(): array
    {
        $choices = [];
        $shipmentTypeCriteriaTransfer = new ShipmentTypeCriteriaTransfer();
        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);

        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $choices[$shipmentTypeTransfer->getName()] = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
        }

        return $choices;
    }
}
