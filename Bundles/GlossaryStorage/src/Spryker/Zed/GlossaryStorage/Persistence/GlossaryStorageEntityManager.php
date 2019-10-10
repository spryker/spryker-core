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
     *
     * @return void
     */
    public function saveGlossaryStorageEntities(array $glossaryStorageEntityTransfers): void
    {
        foreach ($glossaryStorageEntityTransfers as $glossaryStorageEntityTransfer) {
            $this->saveGlossaryStorageEntity($glossaryStorageEntityTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer
     *
     * @return void
     */
    protected function saveGlossaryStorageEntity(SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer)
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
