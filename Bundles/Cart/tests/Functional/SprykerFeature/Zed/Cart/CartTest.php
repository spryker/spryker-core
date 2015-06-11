<?php

namespace Functional\SprykerFeature\Zed\Cart;

use Codeception\TestCase\Test;
use Functional\SprykerFeature\Zed\Cart\Fixture\CartFacadeFixture;
use Generated\Shared\Transfer\TaxItemTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartItemsTransfer;
use Generated\Shared\Transfer\CartTransfer;
use SprykerFeature\Zed\Cart\Business\CartFacade;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;

/**
 * @group Business
 * @group Zed
 * @group Cart
 * @group CartTest
 */
class CartTest extends Test
{
    const DUMMY_PRICE_TYPE = 'DUMMY';
    const DUMMY_1_SKU_ABSTRACT_PRODUCT = 'ABSTRACT1';
    const DUMMY_1_SKU_CONCRETE_PRODUCT = 'CONCRETE1';
    const DUMMY_1_PRICE = 99;
    const DUMMY_2_SKU_ABSTRACT_PRODUCT = 'ABSTRACT2';
    const DUMMY_2_SKU_CONCRETE_PRODUCT = 'CONCRETE2';
    const DUMMY_2_PRICE = 100;

    /**
     * @var CartFacade
     */
    private $cartFacade;

    /**
     * @var PriceFacade
     */
    private $priceFacade;

    /**
     * @var Locator
     */
    private $locator;

    public function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();

        //use fixture here which wraps the original facade to override DI and Settings to not tests plugins
        $this->cartFacade = new CartFacadeFixture(new Factory('Cart'), $this->locator);
        $this->priceFacade = new PriceFacade(new Factory('Price'), $this->locator);

        $this->setTestData();
    }

    public function testAddToCart()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItemTax = new TaxItemTransfer();
        $cartItemTax->setPercentage(10);
        $cartItem->setTax($cartItemTax);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItemTax = new TaxItemTransfer();
        $newItemTax->setPercentage(10);
        $newItem->setTax($newItemTax);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var CartItemTransfer $item */
        foreach ($cart->getItems() as $item) {
            if ($item->getId() === $cartItem->getId()) {
                $this->assertEquals($cartItem->getQuantity(), $item->getQuantity());
            } elseif ($newItem->getId() === $item->getId()) {
                $this->assertEquals($newItem->getQuantity(), $item->getQuantity());
            } else {
                $this->fail('Cart has a unknown item inside');
            }
        }
    }

    public function testIncreaseCartQuantity()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItemTax = new TaxItemTransfer();
        $cartItemTax->setPercentage(10);
        $cartItem->setTax($cartItemTax);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItemTax = new TaxItemTransfer();
        $newItemTax->setPercentage(10);
        $newItem->setTax($newItemTax);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->increaseQuantity($cartChange);

        $this->assertCount(1, $changedCart->getItems());
        /** @var CartItemTransfer $item */
        $changedItem = $changedCart->getItems()[0];
        $this->assertEquals(4, $changedItem->getQuantity());
    }

    public function testRemoveFromCart()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(1);
        $cartItemTax = new TaxItemTransfer();
        $cartItemTax->setPercentage(10);
        $cartItem->setTax($cartItemTax);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItemTax = new TaxItemTransfer();
        $newItemTax->setPercentage(10);
        $newItem->setTax($newItemTax);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
    }

    public function testDecreaseCartItem()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItemTax = new TaxItemTransfer();
        $cartItemTax->setPercentage(10);
        $cartItem->setTax($cartItemTax);
        $cart->addItem($cartItem);

        $newItems = new CartItemsTransfer();
        $newItem = new CartItemTransfer();
        $newItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItemTax = new TaxItemTransfer();
        $newItemTax->setPercentage(10);
        $newItem->setTax($newItemTax);
        $newItems->addCartItem($newItem);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->setChangedCartItems($newItems);

        $changedCart = $this->cartFacade->decreaseQuantity($cartChange);

        $this->assertCount(1, $changedCart->getItems());
        /** @var CartItemTransfer $item */
        $changedItem = $changedCart->getItems()[0];
        $this->assertEquals(2, $changedItem->getQuantity());
    }

    protected function setTestData()
    {
        $defaultPriceType = SpyPriceTypeQuery::create()->filterByName(self::DUMMY_PRICE_TYPE)->findOneOrCreate();
        $defaultPriceType->setName(self::DUMMY_PRICE_TYPE)->save();

        $abstractProduct1 = SpyAbstractProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate()
        ;
        $abstractProduct1->setSku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)->save();

        $concreteProduct1 = SpyProductQuery::create()->filterBySku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)->findOneOrCreate();
        $concreteProduct1->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)->setSpyAbstractProduct($abstractProduct1)->save();

        $abstractProduct2 = SpyAbstractProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate()
        ;
        $abstractProduct2->setSku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)->save();

        $concreteProduct2 = SpyProductQuery::create()->filterBySku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)->findOneOrCreate();
        $concreteProduct2->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)->setSpyAbstractProduct($abstractProduct2)->save();
    }
}
