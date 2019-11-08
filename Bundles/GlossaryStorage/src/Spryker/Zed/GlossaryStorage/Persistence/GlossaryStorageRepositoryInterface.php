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
     * @param int[] $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findGlossaryStorageEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\GlossaryStorageTransfer[]
     */
    public function findFilteredGlossaryStorageEntities(FilterTransfer $filterTransfer, array $ids): array;

    /**
     * @param int[] $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function findFilteredGlossaryKeyEntities(FilterTransfer $filterTransfer): array;
}
