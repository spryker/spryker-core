<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Dependency\Service;

class SalesQuantityToUtilPriceServiceBridge implements SalesQuantityToUtilPriceServiceInterface
{
    /**
     * @var \Spryker\Service\UtilPrice\UtilPriceServiceInterface
     */
    protected $utilPriceService;

    /**
     * @param \Spryker\Service\UtilPrice\UtilPriceServiceInterface $utilPriceService
     */
    public function __construct($utilPriceService)
    {
        $this->utilPriceService = $utilPriceService;
    }

    /**
     * @param float $price
     *
     * @return int
     */
    public function roundPrice(float $price): int
    {
        return $this->utilPriceService->roundPrice($price);
    }
}
