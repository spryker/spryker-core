<?php

namespace SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\SharedResourceKeyBuilder;
use SprykerFeature\Zed\Category\CategoryConfig;

abstract class SharedCategoryResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE;
    }
}
