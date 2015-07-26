<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder;

use SprykerFeature\Shared\Collector\Code\KeyBuilder\SharedResourceKeyBuilder;
use SprykerFeature\Shared\Product\ProductConfig;

abstract class SharedAbstractProductResourceKeyBuilder extends SharedResourceKeyBuilder
{

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return ProductConfig::RESOURCE_TYPE_ABSTRACT_PRODUCT;
    }

}
