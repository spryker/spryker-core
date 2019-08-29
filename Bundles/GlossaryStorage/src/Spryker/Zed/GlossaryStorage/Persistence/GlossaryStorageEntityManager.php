<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStoragePersistenceFactory getFactory()
 */
class GlossaryStorageEntityManager extends AbstractEntityManager implements GlossaryStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[] $glossaryStorageEntityTransfers
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    public function saveGlossaryStorageEntities(array $glossaryStorageEntityTransfers, bool $isSendingToQueue): void
    {
        foreach ($glossaryStorageEntityTransfers as $glossaryStorageEntityTransfer) {
            $this->saveGlossaryStorageEntity($glossaryStorageEntityTransfer, $isSendingToQueue);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer
     * @param bool $isSendingToQueue
     *
     * @return void
     */
    protected function saveGlossaryStorageEntity(SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer, bool $isSendingToQueue)
    {
        $glossaryStorageEntityTransfer->requireFkGlossaryKey();

        $glossaryStorage = $this->getFactory()
            ->createGlossaryStorageQuery()
            ->filterByFkGlossaryKey($glossaryStorageEntityTransfer->getFkGlossaryKey())
            ->filterByLocale($glossaryStorageEntityTransfer->getLocale())
            ->findOneOrCreate();

        $glossaryStorage = $this->getFactory()
            ->createGlossaryStorageMapper()
            ->hydrateSpyGlossaryStorageEntity($glossaryStorage, $glossaryStorageEntityTransfer);

        $glossaryStorage->setIsSendingToQueue($isSendingToQueue);

        $glossaryStorage->save();
    }

    /**
     * @param int $idGlossaryStorage
     *
     * @return void
     */
    public function deleteGlossaryStorageEntity(int $idGlossaryStorage): void
    {
        $glossaryStorage = $this->getFactory()
            ->createGlossaryStorageQuery()
            ->filterByIdGlossaryStorage($idGlossaryStorage)
            ->findOne();

        if ($glossaryStorage) {
            $glossaryStorage->delete();
        }
    }
}
