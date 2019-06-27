<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

class AvailabilityOfferConnectorToAvailabilityFacadeBridge implements AvailabilityOfferConnectorToAvailabilityFacadeInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function calculateStockForProductWithStore($sku, StoreTransfer $storeTransfer): int
    {
        return $this->availabilityFacade->calculateStockForProductWithStore($sku, $storeTransfer);
    }
}
