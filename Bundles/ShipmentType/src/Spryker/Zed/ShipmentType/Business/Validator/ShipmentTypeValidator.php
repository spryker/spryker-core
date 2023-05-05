<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;

class ShipmentTypeValidator implements ShipmentTypeValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface>
     */
    protected array $shipmentTypeValidatorRules = [];

    /**
     * @param list<\Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeValidatorRuleInterface> $shipmentTypeValidatorRules
     */
    public function __construct(array $shipmentTypeValidatorRules)
    {
        $this->shipmentTypeValidatorRules = $shipmentTypeValidatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function validateCollection(ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer): ShipmentTypeCollectionResponseTransfer
    {
        $shipmentTypeTransfers = $shipmentTypeCollectionRequestTransfer->getShipmentTypes();
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($this->shipmentTypeValidatorRules as $shipmentTypeValidatorRule) {
            $errorCollectionTransfer = $shipmentTypeValidatorRule->validate($shipmentTypeTransfers, $errorCollectionTransfer);
        }

        return (new ShipmentTypeCollectionResponseTransfer())
            ->setShipmentTypes($shipmentTypeTransfers)
            ->setErrors($errorCollectionTransfer->getErrors());
    }
}
