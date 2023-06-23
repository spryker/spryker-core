<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Extractor;

interface PickingListExtractorInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return list<string>
     */
    public function extractUuids(array $pickingListTransfers): array;
}
