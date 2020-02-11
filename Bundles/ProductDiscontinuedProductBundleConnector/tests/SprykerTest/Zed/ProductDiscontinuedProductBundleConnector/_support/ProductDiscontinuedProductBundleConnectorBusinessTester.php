<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedProductBundleConnector;

use Codeception\Actor;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductDiscontinuedProductBundleConnectorBusinessTester extends Actor
{
    use _generated\ProductDiscontinuedProductBundleConnectorBusinessTesterActions;

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinued(): ProductDiscontinuedFacadeInterface
    {
        return $this->getLocator()->productDiscontinued()->facade();
    }
}
