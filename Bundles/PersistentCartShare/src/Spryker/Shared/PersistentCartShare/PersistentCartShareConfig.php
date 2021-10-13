<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PersistentCartShare;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PersistentCartShareConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    public const RESOURCE_TYPE_QUOTE = 'quote';

    /**
     * @var string
     */
    public const SHARE_OPTION_KEY_PREVIEW = 'PREVIEW';
}
