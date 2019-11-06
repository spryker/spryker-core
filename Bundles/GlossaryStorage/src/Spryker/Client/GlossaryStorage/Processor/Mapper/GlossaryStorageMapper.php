<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Processor\Mapper;

use Generated\Shared\Transfer\GlossaryStorageTransfer;

class GlossaryStorageMapper implements GlossaryStorageMapperInterface
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
    ): GlossaryStorageTransfer {
        return $glossaryStorageTransfer->fromArray($glossaryStorageDataItem, true);
    }

    /**
     * @param array $glossaryStorageDataItems
     *
     * @return \Generated\Shared\Transfer\GlossaryStorageTransfer[]
     */
    public function mapGlossaryStorageDataItemsToGlossaryStorageTransfers(array $glossaryStorageDataItems): array
    {
        $glossaryStorageTransfers = [];

        foreach ($glossaryStorageDataItems as $glossaryStorageDataItem) {
            $glossaryStorageTransfers[] = $this->mapGlossaryStorageDataItemToGlossaryStorageTransfer(
                $glossaryStorageDataItem,
                new GlossaryStorageTransfer()
            );
        }

        return $glossaryStorageTransfers;
    }
}
