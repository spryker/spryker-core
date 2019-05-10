<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlossaryStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class GlossaryStorageConfig extends AbstractBundleConfig
{
    public const SYNC_STORAGE_QUEUE = 'sync.storage.translation';
    public const SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.translation.error';
    public const RESOURCE_NAME = 'translation';
}
