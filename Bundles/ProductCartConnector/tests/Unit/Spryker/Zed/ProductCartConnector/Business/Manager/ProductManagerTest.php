<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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

        $productConcreteTransfer = new ProductConcreteTransfer();
        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::TAX_SET_NAME);
        $productConcreteTransfer->setTaxSet($taxSetTransfer);

        $productManager = $this->getProductManager($productConcreteTransfer);
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
     * @param ProductConcreteTransfer $returnValue
     *
     * @return ProductManager
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
     * @return ProductFacade|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockProductFacade()
    {
        return $this->getMock(ProductCartConnectorToProductInterface::class, ['getProductConcrete'], [], '', false);
    }

}
