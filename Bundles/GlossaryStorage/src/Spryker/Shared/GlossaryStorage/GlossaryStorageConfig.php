<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlossaryStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class GlossaryStorageConfig extends AbstractBundleConfig
{
    public const SYNC_QUEUE_NAME = 'sync.storage.translation';
    public const RESOURCE_NAME = 'translation';
}
