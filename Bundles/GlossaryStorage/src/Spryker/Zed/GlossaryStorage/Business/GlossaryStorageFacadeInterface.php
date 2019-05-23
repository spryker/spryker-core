<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

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
    public function deleteGlossaryStorageCollectionGlossaryKeyByGlossaryKeyEvents(array $eventTransfers);

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
    public function writeGlossaryStorageCollectionByGlossaryTranslationEvents(array $eventTransfers);
}
