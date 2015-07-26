<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use SprykerFeature\Shared\Cms\CmsConfig;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

class PageResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CmsConfig::RESOURCE_TYPE_PAGE;
    }

}
