<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization;

use Spryker\Service\Kernel\AbstractBundleConfig;

class SynchronizationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     *  - Used for backward compatibility to switch to the new single-key format.
     *  - Defaults to `false`, using the `key:` name format.
     *  - When set to `true`, the single-key format is `key`.
     *
     * @api
     *
     * @deprecated Will be removed in the next major without replacement. Will be switched to normalized format.
     *
     * @return bool
     */
    public function isSingleKeyFormatNormalized(): bool
    {
        return false;
    }
}
