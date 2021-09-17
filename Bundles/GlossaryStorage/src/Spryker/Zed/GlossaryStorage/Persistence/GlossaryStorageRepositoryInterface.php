<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

interface GlossaryStorageRepositoryInterface
{
    /**
     * @param array<int> $glossaryKeyIds
     *
     * @return array<\Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer>
     */
    public function findGlossaryStorageEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param int $offset
     * @param int $limit
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function findGlossaryStorageDataTransferByIds(int $offset, int $limit, array $ids): array;

    /**
     * @param array<int> $glossaryKeyIds
     *
     * @return array<\Generated\Shared\Transfer\SpyGlossaryTranslationEntityTransfer>
     */
    public function findGlossaryTranslationEntityTransfer(array $glossaryKeyIds): array;

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\GlossaryKeyTransfer>
     */
    public function findFilteredGlossaryKeyEntities(int $offset, int $limit): array;
}
