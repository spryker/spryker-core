<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SharedCart;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SharedCartConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    public const QUOTE_RESOURCE_TYPE = 'quote';

    public const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';
    public const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';
    public const PERMISSION_GROUP_OWNER_ACCESS = 'OWNER_ACCESS';
    public const PERMISSION_CONFIG_ID_QUOTE_COLLECTION = 'id_quote_collection';
    public const SHARE_OPTION_GROUP_INTERNAL = 'internal';
}
