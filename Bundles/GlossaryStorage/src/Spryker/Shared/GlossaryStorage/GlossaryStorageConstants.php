<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlossaryStorage;

class GlossaryStorageConstants
{

    /**
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    const SYNC_STORAGE_QUEUE = 'sync.storage.translation';

    /**
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    const SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.translation.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    const RESOURCE_NAME = 'translation';

}
