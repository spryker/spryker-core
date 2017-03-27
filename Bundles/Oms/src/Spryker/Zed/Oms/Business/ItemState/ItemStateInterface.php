<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\ItemState;

use Generated\Shared\Transfer\ItemStateTransfer;

interface ItemStateInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemStateTransfer $itemStateTransfer
     *
     * @return \Generated\Shared\Transfer\ItemStateTransfer
     */
    public function createItemState(ItemStateTransfer $itemStateTransfer);

}
