<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Grouper;

use Generated\Shared\Transfer\PickingListTransfer;

interface PickingListItemGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemTransfer>
     */
    public function getPickingListItemTransferCollectionIndexedByUuid(
        PickingListTransfer $pickingListTransfer
    ): array;
}
