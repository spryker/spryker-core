<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ProductConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getDestinationDirectoryForUploads()
    {
        return \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath('import/products');
    }

}
