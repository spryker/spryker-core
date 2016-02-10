<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Business;

use Generated\Shared\Transfer\GroupableContainerTransfer;

interface ItemGrouperCheckoutConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $orderItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $orderItems);

}
