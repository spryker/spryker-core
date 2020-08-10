<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlossaryStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class GlossaryStorageConstants
{
    /**
     * @deprecated Use {@link \Spryker\Shared\GlossaryStorage\GlossaryStorageConfig::SYNC_STORAGE_TRANSLATION} instead
     *
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    public const SYNC_STORAGE_QUEUE = 'sync.storage.translation';

    /**
     * @deprecated Use {@link \Spryker\Shared\GlossaryStorage\GlossaryStorageConfig::SYNC_ERROR_STORAGE_TRANSLATION} instead
     *
     * Specification:
     * - Queue name as used for processing translation messages
     *
     * @api
     */
    public const SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.translation.error';

    /**
     * @deprecated Use {@link \Spryker\Shared\GlossaryStorage\GlossaryStorageConfig::TRANSLATION_RESOURCE_NAME} instead
     *
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const RESOURCE_NAME = 'translation';
}
