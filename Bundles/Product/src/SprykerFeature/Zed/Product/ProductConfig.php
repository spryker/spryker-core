<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Product\ProductConfig as SharedProductConfig;

class ProductConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getDestinationDirectoryForUploads()
    {
        return \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath('import/products');
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(SharedProductConfig::RESOURCE_TYPE_HOST_YVES);
    }

}
