<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Extractor;

class PickingListExtractor implements PickingListExtractorInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return list<int>
     */
    public function extractIds(array $pickingListTransfers): array
    {
        $pickingListIds = [];
        foreach ($pickingListTransfers as $pickingListTransfer) {
            $pickingListIds[] = $pickingListTransfer->getIdPickingListOrFail();
        }

        return $pickingListIds;
    }
}
