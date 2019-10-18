<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageBusinessFactory;
use Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacade;

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
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductAlternativeStorageCommunicationTester extends Actor
{
    use _generated\ProductAlternativeStorageCommunicationTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface
     */
    public function getProductAlternativeFacade(): ProductAlternativeFacadeInterface
    {
        return $this->getLocator()->productAlternative()->facade();
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string[] $alternativeProductSkus
     *
     * @return void
     */
    public function persistAlternativeForConcreteProduct(ProductConcreteTransfer $productConcreteTransfer, array $alternativeProductSkus): void
    {
        foreach ($alternativeProductSkus as $alternativeProductSku) {
            $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
                ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
                ->setAlternativeSku($alternativeProductSku);
            $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        }

        $this->getProductAlternativeFacade()->persistProductAlternative($productConcreteTransfer);
    }
}
