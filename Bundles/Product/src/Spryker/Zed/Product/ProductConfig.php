<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product;

use Spryker\Shared\Library\DataDirectory;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

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
        return $this->get(ProductConstants::HOST_YVES);
    }

}
