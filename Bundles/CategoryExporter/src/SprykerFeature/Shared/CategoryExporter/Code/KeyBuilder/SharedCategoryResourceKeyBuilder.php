<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder;

use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

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
