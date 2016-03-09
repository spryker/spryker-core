<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Business;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ItemGrouperWishlistConnector\Business\ItemGrouperWishlistConnectorBusinessFactory getFactory()
 */
class ItemGrouperWishlistConnectorFacade extends AbstractFacade
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $items
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupOrderItems(GroupableContainerTransfer $items)
    {
        return $this->getFactory()->getItemGrouperFacade()->groupItemsByKey($items);
    }

}
