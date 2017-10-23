<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\KeyBuilder;

use Spryker\Shared\CmsBlock\CmsBlockConfig;
use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;

class CmsBlockKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK;
    }
}
