<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer;

interface GlossaryStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer
     * @param bool $isSendingToQueue
     * @param array $data
     *
     * @return void
     */
    public function saveGlossaryStorageEntity(SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer, bool $isSendingToQueue, array $data): void;

    /**
     * @param int $idGlossaryStorage
     *
     * @return void
     */
    public function deleteGlossaryStorageEntity(int $idGlossaryStorage): void;
}
