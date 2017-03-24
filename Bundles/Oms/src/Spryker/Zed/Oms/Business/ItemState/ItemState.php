<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\State;

use Generated\Shared\Transfer\ItemStateTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;

class ItemState implements ItemStateInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemStateTransfer $itemStateTransfer
     *
     * @return void
     */
    public function createItemState(ItemStateTransfer $itemStateTransfer)
    {
        $itemStateEntity = new SpyOmsOrderItemState();
        $itemStateEntity->fromArray($itemStateTransfer->toArray());
        $itemStateEntity->save();

        $itemStateTransfer->fromArray($itemStateEntity->toArray());
    }

}
