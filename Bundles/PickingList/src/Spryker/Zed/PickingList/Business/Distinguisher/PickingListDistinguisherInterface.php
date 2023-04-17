<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Distinguisher;

use ArrayObject;

interface PickingListDistinguisherInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer>
     */
    public function setModifiedAttributes(ArrayObject $pickingListTransfers): ArrayObject;
}
