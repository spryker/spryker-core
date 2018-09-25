<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Synchronization;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SynchronizationConstants
{
    public const DEFAULT_SYNC_STORAGE_QUEUE_MESSAGE_CHUNK_SIZE = 'SYNCHRONIZATION:DEFAULT_SYNC_STORAGE_QUEUE_MESSAGE_CHUNK_SIZE';
    public const DEFAULT_SYNC_SEARCH_QUEUE_MESSAGE_CHUNK_SIZE = 'SYNCHRONIZATION:DEFAULT_SYNC_SEARCH_QUEUE_MESSAGE_CHUNK_SIZE';
    public const EXPORT_MESSAGE_CHUNK_SIZE = 'SYNCHRONIZATION:EXPORT_MESSAGE_CHUNK_SIZE';
}
