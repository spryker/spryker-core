<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

interface GlossaryStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[] $glossaryStorageEntityTransfers
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    public function saveGlossaryStorageEntities(array $glossaryStorageEntityTransfers, bool $isSendingToQueue): void;

    /**
     * @param int $idGlossaryStorage
     *
     * @return void
     */
    public function deleteGlossaryStorageEntity(int $idGlossaryStorage): void;
}
