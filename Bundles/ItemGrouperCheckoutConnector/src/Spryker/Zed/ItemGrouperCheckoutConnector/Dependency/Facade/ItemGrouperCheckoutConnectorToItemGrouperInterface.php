<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Dependency\Facade;

use Generated\Shared\Transfer\GroupableContainerTransfer;

interface ItemGrouperCheckoutConnectorToItemGrouperInterface
{

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupAbleItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems);

}
