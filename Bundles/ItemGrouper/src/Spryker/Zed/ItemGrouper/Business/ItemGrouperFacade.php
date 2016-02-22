<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouper\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\GroupableContainerTransfer;

/**
 * @method \Spryker\Zed\ItemGrouper\Business\ItemGrouperBusinessFactory getFactory()
 */
class ItemGrouperFacade extends AbstractFacade implements ItemGrouperFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupAbleItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems)
    {
        return $this->getFactory()->createGrouper()->groupByKey($groupAbleItems);
    }

    /**
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupableItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKeyForNewCollection(GroupableContainerTransfer $groupableItems)
    {
        return $this->getFactory()
            ->createGrouper($regroupAllItemCollection = true)
            ->groupByKey($groupableItems);
    }

}
