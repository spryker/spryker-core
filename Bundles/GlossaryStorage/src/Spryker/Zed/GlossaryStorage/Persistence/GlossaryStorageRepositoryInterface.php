<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

interface GlossaryStorageRepositoryInterface
{
    /**
     * @param int[] $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findGlossaryStorageEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findGlossaryStorageDataTransferByIds(int $offset, int $limit, array $ids): array;

    /**
     * @param int[] $glossaryKeyIds
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer[]
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function findFilteredGlossaryKeyEntities(int $offset, int $limit): array;
}
