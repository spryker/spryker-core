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
     * @var int[]
     */
    protected $idExcludedCarrierFields;

    /**
     * @param \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface $shipmentFacade
     * @param int[] $idExcludedCarrierFields
     * @param array $options
     */
    public function __construct(ShipmentGuiToShipmentFacadeInterface $shipmentFacade, array $idExcludedCarrierFields, array $options = [])
    {
        parent::__construct($options);

        $this->shipmentFacade = $shipmentFacade;
        $this->idExcludedCarrierFields = $idExcludedCarrierFields;
    }

    /**
     * @return \Spryker\Zed\ShipmentGui\Dependency\Facade\ShipmentGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentGuiToShipmentFacadeInterface
    {
        return $this->shipmentFacade;
    }

    /**
     * @return int[]
     */
    public function getIdExcludedCarrierFields(): array
    {
        return $this->idExcludedCarrierFields;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}
