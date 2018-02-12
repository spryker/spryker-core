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
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds);
}
