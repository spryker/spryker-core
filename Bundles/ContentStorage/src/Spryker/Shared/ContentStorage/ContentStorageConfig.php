<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ContentStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ContentStorageConfig
{
    /**
     * Specification:
     * - Term key as used for storage value structure.
     *
     * @api
     */
    public const TERM_KEY = 'term';

    /**
     * Specification:
     * - Content key as used for storage value structure.
     *
     * @api
     */
    public const CONTENT_KEY = 'parameters';

    /**
     * Specification:
     * - Content key as used for storage value structure.
     *
     * @api
     */
    public const ID_CONTENT = 'idContent';

    /**
     * Specification:
     * - Queue name as used for processing content messages.
     *
     * @api
     */
    public const CONTENT_SYNC_STORAGE_QUEUE = 'sync.storage.content';

    /**
     * Specification:
     * - Queue name as used for error content messages.
     *
     * @api
     */
    public const CONTENT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.content.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating.
     *
     * @api
     */
    public const CONTENT_RESOURCE_NAME = 'content';
}
