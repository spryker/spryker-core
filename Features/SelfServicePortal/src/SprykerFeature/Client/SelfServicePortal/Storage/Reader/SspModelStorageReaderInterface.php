<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Storage\Reader;

use Generated\Shared\Transfer\SspModelStorageCollectionTransfer;
use Generated\Shared\Transfer\SspModelStorageCriteriaTransfer;

interface SspModelStorageReaderInterface
{
    public function getSspModelStorageCollection(
        SspModelStorageCriteriaTransfer $sspModelStorageCriteriaTransfer
    ): SspModelStorageCollectionTransfer;

    /**
     * @param list<int> $modelIds
     *
     * @return list<\Generated\Shared\Transfer\SspModelStorageTransfer>
     */
    public function getSspModelStoragesByIds(array $modelIds): array;
}
