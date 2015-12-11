<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Library\DataDirectory;
use SprykerFeature\Shared\Product\ProductConstants;

class ProductConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getDestinationDirectoryForUploads()
    {
        return DataDirectory::getLocalStoreSpecificPath('import/products');
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(ProductConstants::RESOURCE_TYPE_HOST_YVES);
    }

}
