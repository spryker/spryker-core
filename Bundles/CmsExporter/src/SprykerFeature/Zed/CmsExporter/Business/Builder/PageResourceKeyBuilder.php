<?php

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use SprykerFeature\Shared\Cms\CmsResourceSettings;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\SharedResourceKeyBuilder;

class PageResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CmsResourceSettings::RESOURCE_TYPE_PAGE;
    }
}
