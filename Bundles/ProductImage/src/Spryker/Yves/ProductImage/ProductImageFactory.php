<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductImage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductImage\Mapper\StorageImageMapper;

class ProductImageFactory extends AbstractFactory
{

    /***
     * @return \Spryker\Yves\ProductImage\Mapper\StorageImageMapper
     */
    protected function createStorageImageMapper()
    {
        return new StorageImageMapper();
    }
}
