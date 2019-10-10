<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Mapper;

class GlossaryTranslationStorageMapper implements GlossaryTranslationStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[] $glossaryStorageEntityTransfers
     *
     * @return array
     */
    public function mapGlossaryStorageEntityTransferByGlossaryIdAndLocale(array $glossaryStorageEntityTransfers)
    {
        $glossaryStorageTransfersByIdAndLocale = [];
        foreach ($glossaryStorageEntityTransfers as $glossaryStorageEntityTransfer) {
            $glossaryStorageTransfersByIdAndLocale[$glossaryStorageEntityTransfer->getFkGlossaryKey()][$glossaryStorageEntityTransfer->getLocale()] = $glossaryStorageEntityTransfer;
        }

        return $glossaryStorageTransfersByIdAndLocale;
    }
}
