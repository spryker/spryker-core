<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\CmsExporter\Code\KeyBuilder;

use SprykerFeature\Shared\Cms\CmsConfig;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\SharedResourceKeyBuilder;

class SharedBlockResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return CmsConfig::RESOURCE_TYPE_BLOCK;
    }

}
