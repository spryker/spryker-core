<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\CategoryExporter\Code\KeyBuilder;

use Spryker\Shared\Category\CategoryConstants;
use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

abstract class SharedCategoryResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE;
    }

}
