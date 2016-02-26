<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ItemGrouper\Business;

use Generated\Shared\Transfer\GroupableContainerTransfer;

interface ItemGrouperFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupAbleItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKey(GroupableContainerTransfer $groupAbleItems);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GroupableContainerTransfer $groupableItems
     *
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    public function groupItemsByKeyForNewCollection(GroupableContainerTransfer $groupableItems);

}
