<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\ProductCartConnector\Business\Manager\ProductManager;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

/**
 * @group Spryker
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group ProductCartManager
 */
class ProductManagerTest extends \PHPUnit_Framework_TestCase
{

    const CONCRETE_SKU = 'concrete sku';
    const ABSTRACT_SKU = 'abstract sku';
    const ID_PRODUCT_CONCRETE = 'id concrete product';
    const ID_PRODUCT_ABSTRACT = 'id product abstract';
    const PRODUCT_NAME = 'product name';
    const TAX_SET_NAME = 'tax set name';

    /**
     * @return void
     */
    public function testExpandItemsMustAddProductIdToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setIdConcreteProduct(self::ID_PRODUCT_CONCRETE);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($concreteProductTransfer->getIdConcreteProduct(), $changedItemTransfer->getId());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddAbstractSkuToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setAbstractProductSku(self::ABSTRACT_SKU);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($concreteProductTransfer->getAbstractProductSku(), $changedItemTransfer->getAbstractSku());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddAbstractIdToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setIdProductAbstract(self::ID_PRODUCT_ABSTRACT);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($concreteProductTransfer->getIdProductAbstract(), $changedItemTransfer->getIdProductAbstract());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddProductNameToCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setName(self::PRODUCT_NAME);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($concreteProductTransfer->getName(), $changedItemTransfer->getName());
    }

    /**
     * @return void
     */
    public function testExpandItemsMustAddTaxSetToAllCartItemsIfPRoductHasTaxSet()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::TAX_SET_NAME);
        $concreteProductTransfer->setTaxSet($taxSetTransfer);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $expandedTaxSet = $changedItemTransfer->getTaxSet();
        $this->assertSame($taxSetTransfer, $expandedTaxSet);
    }

    /**
     * @return ChangeTransfer
     */
    private function getChangeTransfer()
    {
        $changeTransfer = new ChangeTransfer();
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(self::CONCRETE_SKU);
        $changeTransfer->addItem($itemTransfer);

        return $changeTransfer;
    }

    /**
     * @param ConcreteProductTransfer $returnValue
     *
     * @return ProductManager
     */
    public function getProductManager(ConcreteProductTransfer $returnValue)
    {
        $mockProductFacade = $this->getMockProductFacade();
        $mockProductFacade->expects($this->once())
            ->method('getConcreteProduct')
            ->will($this->returnValue($returnValue));

        $productManager = new ProductManager($mockProductFacade);

        return $productManager;
    }

    /**
     * @return ProductFacade|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockProductFacade()
    {
        return $this->getMock(ProductCartConnectorToProductInterface::class, ['getConcreteProduct'], [], '', false);
    }

}
