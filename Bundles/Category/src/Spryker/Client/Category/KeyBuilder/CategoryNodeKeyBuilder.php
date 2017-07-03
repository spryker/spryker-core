<?php

namespace Spryker\Client\Category\KeyBuilder;


use Spryker\Shared\Category\CategoryConfig;
use Spryker\Shared\KeyBuilder\SharedResourceKeyBuilder;

class CategoryNodeKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE;
    }

}