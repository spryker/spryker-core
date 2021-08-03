<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Dependency\Service;

class AvailabilityGuiToAvailabilityServiceBridge implements AvailabilityGuiToAvailabilityServiceInterface
{
    /**
     * @var \Spryker\Service\Availability\AvailabilityServiceInterface
     */
    protected $availabilityService;

    /**
     * @param \Spryker\Service\Availability\AvailabilityServiceInterface $availabilityService
     */
    public function __construct($availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * @param string $productConcretesNeverOutOfStockSet
     *
     * @return bool
     */
    public function isAbstractProductNeverOutOfStock(string $productConcretesNeverOutOfStockSet): bool
    {
        return $this->availabilityService->isAbstractProductNeverOutOfStock($productConcretesNeverOutOfStockSet);
    }
}
