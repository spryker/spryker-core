<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Dependency\Facade;

class OfferGuiToOmsFacadeBridge implements OfferGuiToOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[][]
     */
    public function getManualEventsByIdSalesOrder(int $idSalesOrder): array
    {
        return $this->omsFacade->getManualEventsByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return string[]
     */
    public function getDistinctManualEventsByIdSalesOrder(int $idSalesOrder): array
    {
        return $this->omsFacade->getDistinctManualEventsByIdSalesOrder($idSalesOrder);
    }
}
