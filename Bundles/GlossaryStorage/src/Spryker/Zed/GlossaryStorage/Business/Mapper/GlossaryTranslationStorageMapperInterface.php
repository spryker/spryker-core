<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Mapper;

interface GlossaryTranslationStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[] $glossaryStorageEntityEntityTransfers
     *
     * @return array
     */
    public function mapGlossaryStorageEntityTransferByGlossaryIdAndLocale(array $glossaryStorageEntityEntityTransfers);
}
