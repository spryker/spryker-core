<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ConfigurableBundleStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ConfigurableBundleStorageConstants
{
    /**
     * Specification:
     * - Enables/disables storage synchronization.
     *
     * @api
     *
     * @uses \Spryker\Shared\Synchronization\SynchronizationConstants::STORAGE_SYNC_ENABLED
     */
    public const STORAGE_SYNC_ENABLED = 'SYNCHRONIZATION:STORAGE_SYNC_ENABLED';
}
