<?php

namespace SprykerFeature\Sdk\CategoryExporter\KeyBuilder;

use SprykerFeature\Shared\Category\CategoryResourceSettings;
use SprykerFeature\Shared\UrlExporter\Code\KeyBuilder\ResourceKeyBuilder as SharedResourceKeyBuilder;

class ResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE;
    }
}
