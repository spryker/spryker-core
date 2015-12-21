<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency\Facade;

class CartToItemGrouperBridge implements CartToItemGrouperInterface
{

    /**
     * @var \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade
     */
    protected $itemGrouperFacade;

    /**
     * @param \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade $itemGrouperFacade
     */
    public function __construct($itemGrouperFacade)
    {
        $this->itemGrouperFacade = $itemGrouperFacade;
    }


}
