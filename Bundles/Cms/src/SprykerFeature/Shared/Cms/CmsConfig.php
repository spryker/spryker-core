<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Shared\Cms;

use SprykerFeature\Shared\Library\ConfigInterface;

interface CmsConfig extends ConfigInterface
{

    const RESOURCE_TYPE_PAGE = 'page';
    const RESOURCE_TYPE_BLOCK = 'block';
    const RESOURCE_TYPE_CATEGORY_NODE = 'category';
    const RESOURCE_TYPE_STATIC = 'static';

}
