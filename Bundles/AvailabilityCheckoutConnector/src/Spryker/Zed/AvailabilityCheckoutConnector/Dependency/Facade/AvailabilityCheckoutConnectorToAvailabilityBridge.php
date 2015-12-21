<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCheckoutConnector\Dependency\Facade;

class AvailabilityCheckoutConnectorToAvailabilityBridge implements AvailabilityCheckoutConnectorToAvailabilityInterface
{

    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacade
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacade $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }


}
