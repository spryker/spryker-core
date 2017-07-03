<?php

namespace Spryker\Shared\Category;


use Spryker\Client\Kernel\AbstractBundleConfig;

class CategoryConfig extends AbstractBundleConfig
{
    /**
     * Used as `item_type` for touch mechanism.
     */
    const RESOURCE_TYPE_CATEGORY_NODE = 'categorynode';

    /**
     * Used as `item_type` for touch mechanism.
     */
    const RESOURCE_TYPE_NAVIGATION = 'navigation';

}