<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\FileManagerStorage;

interface FileManagerStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing file messages
     *
     * @api
     */
    public const FILE_SYNC_STORAGE_QUEUE = 'sync.storage.file';

    /**
     * Specification:
     * - Queue name as used for processing file messages
     *
     * @api
     */
    public const FILE_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.file.error';
}
