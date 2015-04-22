<?php

namespace SprykerFeature\Zed\Product\Business;

/**
 * Class ProductSettings
 *
 * @package SprykerFeature\Zed\Product\Business
 */
class ProductSettings
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
    public function getProductDefaultLocale()
    {
        return 'de_DE';
    }
}
 