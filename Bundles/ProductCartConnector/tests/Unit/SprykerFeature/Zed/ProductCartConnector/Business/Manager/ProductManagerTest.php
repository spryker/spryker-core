<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\ProductCartConnector\Business\Manager;


use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\ConcreteProductTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\ProductCartConnector\Business\Manager\ProductManager;

/**
 * @group SprykerFeature
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group ProductCartManager
 */
class ProductManagerTest extends \PHPUnit_Framework_TestCase
{

    const CONCRETE_SKU = 'concrete sku';

    const ABSTRACT_SKU = 'abstract sku';

    const ID_CONCRETE_PRODUCT = 'id concrete product';

    const ID_ABSTRACT_PRODUCT = 'id abstract product';

    const PRODUCT_NAME = 'product name';

    const TAX_SET_NAME = 'tax set name';

    public function testExpandItemsMustAddProductIdToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setIdConcreteProduct(self::ID_CONCRETE_PRODUCT);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($concreteProductTransfer->getIdConcreteProduct(), $changedItemTransfer->getId());
    }

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

    public function testExpandItemsMustAddAbstractIdToAllCartItems()
    {
        $changeTransfer = $this->getChangeTransfer();

        $concreteProductTransfer = new ConcreteProductTransfer();
        $concreteProductTransfer->setIdAbstractProduct(self::ID_ABSTRACT_PRODUCT);

        $productManager = $this->getProductManager($concreteProductTransfer);
        $result = $productManager->expandItems($changeTransfer);

        $changedItemTransfer = $result->getItems()[0];
        $this->assertSame($concreteProductTransfer->getIdAbstractProduct(), $changedItemTransfer->getIdAbstractProduct());
    }

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
        return $this->getMock('SprykerFeature\Zed\Product\Business\ProductFacade', ['getConcreteProduct'], [], '', false);
    }

}
