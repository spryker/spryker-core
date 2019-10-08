<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeProductLabelConnector;

use Codeception\Actor;
use Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductAlternativeProductLabelConnectorBusinessTester extends Actor
{
    use _generated\ProductAlternativeProductLabelConnectorBusinessTesterActions;

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface
     */
    public function getProductAlternativeFacade(): ProductAlternativeFacadeInterface
    {
        return $this->getLocator()->productAlternative()->facade();
    }
}
