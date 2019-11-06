<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Processor\Mapper;

use Generated\Shared\Transfer\GlossaryStorageTransfer;

interface GlossaryStorageMapperInterface
{
    /**
     * @param array $glossaryStorageDataItem
     * @param \Generated\Shared\Transfer\GlossaryStorageTransfer $glossaryStorageTransfer
     *
     * @return \Generated\Shared\Transfer\GlossaryStorageTransfer
     */
    public function mapGlossaryStorageDataItemToGlossaryStorageTransfer(
        array $glossaryStorageDataItem,
        GlossaryStorageTransfer $glossaryStorageTransfer
    ): GlossaryStorageTransfer;

    /**
     * @param array $glossaryStorageDataItems
     *
     * @return \Generated\Shared\Transfer\GlossaryStorageTransfer[]
     */
    public function mapGlossaryStorageDataItemsToGlossaryStorageTransfers(array $glossaryStorageDataItems): array;
}
