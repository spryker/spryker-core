<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Shared\Cms;

use SprykerFeature\Shared\Library\ConfigInterface;

interface CmsConfig extends ConfigInterface
{
    const RESOURCE_TYPE_PAGE  = 'page';
    const RESOURCE_TYPE_BLOCK = 'block';
}
