<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface GlossaryStorageFacadeInterface
{
    /**
     * @api
     *
     * @deprecated Use `\Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface::writeGlossaryStorageCollection()` instead.
     *
     * Specification:
     * - Queries all glossary keys with the given glossaryKeyIds
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds);

    /**
     * @api
     *
     * @deprecated Use `\Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface::deleteGlossaryStorageCollection()` instead.
     *
     * Specification:
     * - Finds and deletes glossary storage entities with the given glossaryKeyIds
     * - Sends delete message to queue based on module config
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds);

    /**
     * Specification:
     * - Queries all glossary keys with the given $eventTransfer by GlossaryKeyEvents
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers);

    /**
     * Specification:
     * - Finds and deletes glossary storage entities with the given $eventTransfer by GlossaryKeyEvents
     * - Sends delete message to queue based on module config
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function deleteGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all glossary keys with the given $eventTransfer by GlossaryTranslationEvents
     * - Stores data as json encoded to storage table
     * - Sends a copy of data to queue based on module config
     *
     * @api
     *
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryTranslationEvents(array $eventTransfers): void;

    /**
     * Specification
     * - Retrieves a collection of glossary key entity transfer according to provided offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryKeyEntityTransfer[]
     */
    public function findFilteredGlossaryKeyEntityTransfers(FilterTransfer $filterTransfer): array;

    /**
     * Specification
     * - Retrieves a collection of glossary storage entity transfer according to provided offset, limit and ida.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array $ids
     *
     * @return \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer[]
     */
    public function findFilteredGlossaryStorageEntities(FilterTransfer $filterTransfer, array $ids): array;
}
