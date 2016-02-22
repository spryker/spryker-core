<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouperCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method \Spryker\Zed\ItemGrouperCheckoutConnector\Business\ItemGrouperCheckoutConnectorBusinessFactory getFactory()
 */
class ItemGrouperCheckoutConnectorFacade extends AbstractFacade implements ItemGrouperCheckoutConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $orderItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $orderItems)
    {
        return $this->getFactory()->getItemGrouperFacade()->groupItemsByKey($orderItems);
    }

}
