<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

class AvailabilityToOmsBridge implements AvailabilityToOmsInterface
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
     * @deprecated Using this method will affect the performance,
     * use AvailabilityToOmsInterface::getOmsReservedProductQuantityForSku() instead.
     *
     * @param string $sku
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku)
    {
        return $this->omsFacade->sumReservedProductQuantitiesForSku($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku)
    {
        return $this->omsFacade->getOmsReservedProductQuantityForSku($sku);
    }
}
