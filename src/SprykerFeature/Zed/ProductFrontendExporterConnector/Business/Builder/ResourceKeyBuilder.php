<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Builder;

use SprykerFeature\Shared\UrlExporter\Code\KeyBuilder\ResourceKeyBuilder as SharedResourceKeyBuilder;

class ResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return 'product';
    }
}
