<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOptionsRestApi\Business\Mapper\ProductOptionMapper;
use Spryker\Zed\ProductOptionsRestApi\Business\Mapper\ProductOptionMapperInterface;

/**
 * @method \Spryker\Zed\ProductOptionsRestApi\ProductOptionsRestApiConfig getConfig()
 */
class ProductOptionsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOptionsRestApi\Business\Mapper\ProductOptionMapperInterface
     */
    public function createProductOptionMapper(): ProductOptionMapperInterface
    {
        return new ProductOptionMapper();
    }
}
