<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductCartConnector\Business\Manager\ProductManager;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group Manager
 * @group ProductManagerTest
 */
class ProductManagerTest extends \PHPUnit_Framework_TestCase
{

    const CONCRETE_SKU = 'concrete sku';
    const ABSTRACT_SKU = 'abstract sku';
    const ID_PRODUCT_CONCRETE = 'id product concrete';
    const ID_PRODUCT_ABSTRACT = 'id product abstract';
    const PRODUCT_NAME = 'product name';
    const TAX_SET_NAME = 'tax set name';

    /**
     * @return void
     */
    public function testExpandItemsMustAddProductIdToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete(self::ID_PRODUCT_CONCRETE);

        $productManager = $this->getProductManager($productConcreteTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($productConcreteTransfer->getIdProductConcrete(), $changedItemTransfer->getId());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddAbstractSkuToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setProductAbstractSku(self::ABSTRACT_SKU);

        $productManager = $this->getProductManager($productConcreteTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($productConcreteTransfer->getProductAbstractSku(), $changedItemTransfer->getAbstractSku());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddAbstractIdToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductAbstract(self::ID_PRODUCT_ABSTRACT);

        $productManager = $this->getProductManager($productConcreteTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($productConcreteTransfer->getIdProductAbstract(), $changedItemTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddProductNameToCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setName(self::PRODUCT_NAME);

        $productManager = $this->getProductManager($productConcreteTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($productConcreteTransfer->getName(), $changedItemTransfer->getName());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddTaxSetToAllCartItemsIfPRoductHasTaxSet()
    {
        $changeTransfer = $this->getChangeTransfer();

        $productTaxRate = 19;
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setTaxRate($productTaxRate);

        $productManager = $this->getProductManager($productConcreteTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($productConcreteTransfer->getTaxRate(), $changedItemTransfer->getTaxRate());
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    private function getChangeTransfer()
    {
        $changeTransfer = new CartChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(self::CONCRETE_SKU);
        $changeTransfer->addItem($itemTransfer);

        return $changeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $returnValue
     *
     * @return \Spryker\Zed\ProductCartConnector\Business\Manager\ProductManager
     */
    public function getProductManager(ProductConcreteTransfer $returnValue)
    {
        $mockProductFacade = $this->getMockProductFacade();
        $mockProductFacade->expects($this->once())
            ->method('getProductConcrete')
            ->will($this->returnValue($returnValue));

        $productManager = new ProductManager($mockProductFacade);

        return $productManager;
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacade|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockProductFacade()
    {
        return $this->getMock(ProductCartConnectorToProductInterface::class, ['getProductConcrete'], [], '', false);
    }

}
