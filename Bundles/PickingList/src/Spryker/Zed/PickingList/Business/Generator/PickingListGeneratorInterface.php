<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Generator;

use Generated\Shared\Transfer\GeneratePickingListsRequestTransfer;
use Generated\Shared\Transfer\PickingListCollectionResponseTransfer;

interface PickingListGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionResponseTransfer
     */
    public function generatePickingLists(GeneratePickingListsRequestTransfer $generatePickingListsRequestTransfer): PickingListCollectionResponseTransfer;
}
