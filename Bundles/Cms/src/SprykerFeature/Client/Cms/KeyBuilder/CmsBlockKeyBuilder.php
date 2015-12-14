<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Client\Cms\KeyBuilder;

use SprykerFeature\Shared\Cms\CmsConstants;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

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
