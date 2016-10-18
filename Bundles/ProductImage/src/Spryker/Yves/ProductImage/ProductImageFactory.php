<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductImage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductImage\Builder\StorageImageBuilder;

class ProductImageFactory extends AbstractFactory
{

    /***
     * @return \Spryker\Yves\ProductImage\Builder\StorageImageBuilder
     */
    protected function createStorageImageBuilder()
    {
        return new StorageImageBuilder();
    }
}
