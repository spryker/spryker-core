<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\GlossaryKeyTransfer;
use Generated\Shared\Transfer\GlossaryStorageTransfer;
use Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer;
use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage;

class GlossaryStorageMapper implements GlossaryStorageMapperInterface
{
    /**
     * @param \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage $glossaryStorage
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer
     *
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage
     */
    public function hydrateSpyGlossaryStorageEntity(SpyGlossaryStorage $glossaryStorage, SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer): SpyGlossaryStorage
    {
        $glossaryStorage->fromArray($glossaryStorageEntityTransfer->toArray(true));

        return $glossaryStorage;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[] $glossaryStorageEntityTransfers
     *
     * @return \Generated\Shared\Transfer\GlossaryStorageTransfer[]
     */
    public function hydrateGlossaryStorageTransfer(array $glossaryStorageEntityTransfers): array
    {
        $glossaryStorageTransfers = [];
        foreach ($glossaryStorageEntityTransfers as $spyGlossaryStorageEntityTransfer) {
            $glossaryStorageTransfers[] = (new GlossaryStorageTransfer())->fromArray($spyGlossaryStorageEntityTransfer->toArray(true));
        }

        return $glossaryStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyGlossaryKeyEntityTransfer[] $glossaryKeyEntityTransfers
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function hydrateGlossaryKeyTransfer(array $glossaryKeyEntityTransfers): array
    {
        $glossaryKeyTransfers = [];
        foreach ($glossaryKeyEntityTransfers as $glossaryKeyEntityTransfer) {
            $glossaryKeyTransfers[] = (new GlossaryKeyTransfer())->fromArray($glossaryKeyEntityTransfer->toArray(true), true);
        }

        return $glossaryKeyTransfers;
    }
}
