<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Dependency\Service;

class OfferToUtilProductServiceBridge implements OfferToUtilProductServiceInterface
{
    /**
     * @var \Spryker\Service\UtilProduct\UtilProductServiceInterface
     */
    protected $utilProductService;

    /**
     * @param \Spryker\Service\UtilProduct\UtilProductServiceInterface $utilProductService
     */
    public function __construct($utilProductService)
    {
        $this->utilProductService = $utilProductService;
    }

    /**
     * @param float $price
     *
     * @return int
     */
    public function roundPrice(float $price): int
    {
        return $this->utilProductService->roundPrice($price);
    }
}
