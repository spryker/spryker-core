<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ProductOptionCheckoutConnector\ProductOptionCheckoutConnectorConfig getConfig()
 */
class ProductOptionCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOptionCheckoutConnector\Business\ProductOptionOrderHydrator
     */
    public function createProductOptionOrderHydrator()
    {
        return new ProductOptionOrderHydrator();
    }

}
