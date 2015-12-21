<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\GroupableContainerTransfer;

interface ItemGrouperCheckoutConnectorToItemGrouperInterface
{

    /**
     * @param GroupableContainerTransfer $groupAbleItems
     *
     * @return GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems);

}
