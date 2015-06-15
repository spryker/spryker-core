<?php

namespace SprykerFeature\Zed\Product;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ProductConfig extends AbstractBundleConfig
{
    const RESOURCE_TYPE_PRODUCT = 'product';
    const RESOURCE_TYPE_ABSTRACT_PRODUCT = 'abstract_product';

    /**
     * @return string
     */
    public function getDestinationDirectoryForUploads()
    {
        return \SprykerFeature_Shared_Library_Data::getLocalStoreSpecificPath('import/products');
    }
}
