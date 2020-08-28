<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued;

use Codeception\Actor;
use Spryker\Zed\Product\Business\ProductFacadeInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 */
class ProductDiscontinuedBusinessTester extends Actor
{
    use _generated\ProductDiscontinuedBusinessTesterActions;

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }
}
