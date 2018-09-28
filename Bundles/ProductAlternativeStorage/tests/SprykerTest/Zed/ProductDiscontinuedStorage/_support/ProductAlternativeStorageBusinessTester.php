<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeStorage;

use Codeception\Actor;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageBusinessFactory;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacade;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface;

/**
 * Inherited Methods
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
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductAlternativeStorageBusinessTester extends Actor
{
    use _generated\ProductAlternativeStorageBusinessTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface
     */
    public function getProductDiscontinuedFacade(): ProductDiscontinuedFacadeInterface
    {
        return $this->getLocator()->productDiscontinued()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacade
     */
    public function getMockedFacade(): ProductAlternativeStorageFacade
    {
        $factory = new ProductAlternativeStorageBusinessFactory();
        $factory->setConfig(new ProductAlternativeStorageConfigMock());

        $facade = $this->getFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }
}
