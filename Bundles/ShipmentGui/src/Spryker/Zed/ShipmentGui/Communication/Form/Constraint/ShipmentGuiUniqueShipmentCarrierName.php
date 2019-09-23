<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Constraint;

use Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface;
use Symfony\Component\Validator\Constraint;

class ShipmentGuiUniqueShipmentCarrierName extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Carrier with the same name "{{ name }}" already exists.';

    /**
     * @var \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    protected $shipmentFacade;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface $shipmentFacade
     * @param array $fields
     * @param array $options
     */
    public function __construct(ShipmentGuiToShipmentFacadeInterface $shipmentFacade, array $fields, array $options = [])
    {
        parent::__construct($options);

        $this->shipmentFacade = $shipmentFacade;
        $this->fields = $fields;
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentGuiToShipmentFacadeInterface
    {
        return $this->shipmentFacade;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
