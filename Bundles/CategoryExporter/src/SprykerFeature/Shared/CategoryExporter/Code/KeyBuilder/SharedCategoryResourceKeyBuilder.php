<?php

namespace SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder;

use SprykerFeature\Shared\Category\CategoryResourceSettings;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\SharedResourceKeyBuilder;

abstract class SharedCategoryResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE;
    }
}
