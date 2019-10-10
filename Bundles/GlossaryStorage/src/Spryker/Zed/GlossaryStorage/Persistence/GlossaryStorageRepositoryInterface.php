<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;

interface GlossaryStorageRepositoryInterface
{
    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findGlossaryStorageEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findFilteredGlossaryStorageEntities(FilterTransfer $filterTransfer, array $ids);

    /**
     * @param array $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryKeyEntityTransfer[]
     */
    public function findFilteredGlossaryKeyEntityTransfers(FilterTransfer $filterTransfer): array;
}
