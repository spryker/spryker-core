<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Reader;

use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;

interface ReturnableItemReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer;
}
