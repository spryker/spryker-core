<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class AvailabilityToOmsBridge implements AvailabilityToOmsInterface
{

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacade
     */
    protected $omsFacade;

    /**
     * @param \Spryker\Zed\Oms\Business\OmsFacade $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $sku
     *
     * @return SpySalesOrderItem
     */
    public function countReservedOrderItemsForSku($sku)
    {
        return $this->omsFacade->countReservedOrderItemsForSku($sku);
    }

}
