<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Dependency\Facade;

use Generated\Shared\Transfer\GroupableContainerTransfer;

interface ItemGrouperWishlistConnectorToItemGrouperInterface
{

    /**
     * @param GroupableContainerTransfer $groupAbleItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems);

}
