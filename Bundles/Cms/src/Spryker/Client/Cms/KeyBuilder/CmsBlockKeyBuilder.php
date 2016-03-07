<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms\KeyBuilder;

use Spryker\Shared\Cms\CmsConstants;
use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

class CmsBlockKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_BLOCK;
    }

}
