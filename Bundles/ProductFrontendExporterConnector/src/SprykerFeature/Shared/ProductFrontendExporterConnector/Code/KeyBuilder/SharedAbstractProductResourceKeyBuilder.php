<?php

namespace SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\SharedResourceKeyBuilder;
use SprykerFeature\Shared\Product\ProductResourceSettings;

abstract class SharedAbstractProductResourceKeyBuilder extends SharedResourceKeyBuilder
{
    /**
     * @return string
     */
    protected function getResourceType()
    {
        return ProductResourceSettings::RESOURCE_TYPE_ABSTRACT_PRODUCT;
    }
}