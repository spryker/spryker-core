<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Library\DataDirectory;
use Spryker\Shared\Product\ProductConstants;

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
