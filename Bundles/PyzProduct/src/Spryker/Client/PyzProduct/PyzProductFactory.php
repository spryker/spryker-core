<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PyzProduct;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Product\ProductClient;

class PyzProductFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Product\ProductClient
     */
    public function getProductClient()
    {
        return new ProductClient();
    }

}
