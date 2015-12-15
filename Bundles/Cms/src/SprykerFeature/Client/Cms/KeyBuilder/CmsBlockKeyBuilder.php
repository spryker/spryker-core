<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
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
