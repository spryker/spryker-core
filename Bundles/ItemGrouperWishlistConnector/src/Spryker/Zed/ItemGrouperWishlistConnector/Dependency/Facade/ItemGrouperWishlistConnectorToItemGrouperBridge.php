<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Dependency\Facade;

use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

class ItemGrouperWishlistConnectorToItemGrouperBridge implements ItemGrouperWishlistConnectorToItemGrouperInterface
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

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupAbleItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems)
    {
        return $this->itemGrouperFacade->groupItemsByKey($groupAbleItems);
    }

}
