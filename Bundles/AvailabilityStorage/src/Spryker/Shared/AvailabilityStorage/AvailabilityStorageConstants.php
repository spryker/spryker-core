<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AvailabilityStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class AvailabilityStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing availability messages
     *
     * @api
     */
    public const AVAILABILITY_SYNC_STORAGE_QUEUE = 'sync.storage.availability';

    /**
     * Specification:
     * - Queue name as used for processing availability messages
     *
     * @api
     */
    public const AVAILABILITY_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.availability.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const AVAILABILITY_RESOURCE_NAME = 'availability';
}
