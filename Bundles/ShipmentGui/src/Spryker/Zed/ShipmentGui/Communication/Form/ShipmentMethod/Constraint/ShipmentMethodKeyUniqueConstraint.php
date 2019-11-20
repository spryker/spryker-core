<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\ShipmentMethod\Constraint;

use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Symfony\Component\Validator\Constraint;

class ShipmentMethodKeyUniqueConstraint extends Constraint
{
    public const OPTION_SHIPMENT_FACADE = 'shipmentFacade';

    /**
     * @var string
     */
    protected $message = 'This shipment method key is already in use.';

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentGuiToShipmentFacadeInterface
    {
        return $this->shipmentFacade;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
