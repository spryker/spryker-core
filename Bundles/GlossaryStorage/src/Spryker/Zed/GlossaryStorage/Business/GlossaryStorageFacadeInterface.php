<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

interface GlossaryStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all glossary keys with the given glossaryKeyIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface::writeGlossaryStorageCollection()} instead.
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds);

    /**
     * Specification:
     * - Finds and deletes glossary storage entities with the given glossaryKeyIds
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface::deleteGlossaryStorageCollection()} instead.
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds);

    /**
     * Specification:
     * - Queries all glossary keys with the given $eventTransfer by GlossaryKeyEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByGlossaryKeyEvents(array $eventTransfers);

    /**
     * Specification:
     * - Finds and deletes glossary storage entities with the given $eventTransfer by GlossaryKeyEvents.
     * - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByGlossaryKeyEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all glossary keys with the given $eventTransfer by GlossaryTranslationEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByGlossaryTranslationEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Retrieves a collection of glossary key transfer according to provided offset and limit.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function findFilteredGlossaryKeyEntities(int $offset, int $limit): array;

    /**
     * Specification:
     * - Retrieves a collection of glossary storage transfer according to provided offset, limit and ids.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function findGlossaryStorageDataTransferByIds(int $offset, int $limit, array $ids): array;
}
