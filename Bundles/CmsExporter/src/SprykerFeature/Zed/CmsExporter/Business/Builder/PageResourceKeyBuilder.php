<?php

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\SharedResourceKeyBuilder;
use SprykerFeature\Zed\Cms\CmsConfig;

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
