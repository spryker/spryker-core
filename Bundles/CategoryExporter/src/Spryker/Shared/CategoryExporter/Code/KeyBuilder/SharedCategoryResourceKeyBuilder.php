<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\CategoryExporter\Code\KeyBuilder;

use Spryker\Shared\CategoryExporter\CategoryExporterConstants;
use Spryker\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;

abstract class SharedCategoryResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryExporterConstants::RESOURCE_TYPE_CATEGORY_NODE;
    }

}
