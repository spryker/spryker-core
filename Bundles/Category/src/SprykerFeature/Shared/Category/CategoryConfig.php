<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Category;

use SprykerFeature\Shared\Library\ConfigInterface;

interface CategoryConfig extends ConfigInterface
{

    const RESOURCE_TYPE_CATEGORY_NODE = 'categorynode';
    const RESOURCE_TYPE_NAVIGATION = 'navigation';

}
