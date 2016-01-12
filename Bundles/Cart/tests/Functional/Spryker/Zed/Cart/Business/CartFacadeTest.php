<?php

namespace Functional\Spryker\Zed\Cart\Business;

use Codeception\TestCase\Test;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Spryker\Zed\Kernel\Container;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Spryker\Zed\Cart\Business\CartFacade;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Price\Business\PriceFacade;
use Orm\Zed\Price\Persistence\SpyPriceProductQuery;
use Orm\Zed\Price\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;

/**
 * @group Spryker
 * @group Zed
 * @group Cart
 * @group Business
 * @group CartFacadeTest
 */
class CartFacadeTest extends Test
{

    const PRICE_TYPE_DEFAULT = 'DEFAULT';
    const DUMMY_1_SKU_PRODUCT_ABSTRACT = 'ABSTRACT1';
    const DUMMY_1_SKU_PRODUCT_CONCRETE = 'CONCRETE1';

    const DUMMY_2_SKU_PRODUCT_ABSTRACT = 'ABSTRACT2';
    const DUMMY_2_SKU_PRODUCT_CONCRETE = 'CONCRETE2';

    /**
     * @var CartFacade
     */
    private $cartFacade;

    /**
     * @var PriceFacade
     */
    private $priceFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->cartFacade = new CartFacade();
        $this->priceFacade = new PriceFacade();

        $this->setTestData();
    }

    /**
     * @return void
     */
    public function testAddToCart()
    {
        $cart = new CartTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_PRODUCT_CONCRETE);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_2_SKU_PRODUCT_CONCRETE);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var ItemTransfer $item */
        foreach ($cart->getItems() as $item) {
            if ($item->getSku() === $cartItem->getSku()) {
                $this->assertEquals($cartItem->getQuantity(), $item->getQuantity());
            } elseif ($newItem->getSku() === $item->getSku()) {
                $this->assertEquals($newItem->getQuantity(), $item->getQuantity());
            } else {
                $this->fail('Cart has a unknown item inside');
            }
        }
    }

    /**
     * @return void
     */
    public function testIncreaseCartQuantity()
    {
        $cart = new CartTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_PRODUCT_CONCRETE);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_1_SKU_PRODUCT_CONCRETE);
        $newItem->setSku(self::DUMMY_1_SKU_PRODUCT_CONCRETE);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->increaseQuantity($cartChange);
        $cartItems = $changedCart->getItems();
        $this->assertCount(2, $cartItems);

        /** @var ItemTransfer $changedItem */
        $changedItem = $cartItems[1];
        $this->assertEquals(3, $changedItem->getQuantity());

        /* @TODO check key names https://github.com/spryker/spryker/issues/1128 */
        $changedItem = $cartItems['CONCRETE1'];
        $this->assertEquals(1, $changedItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testRemoveFromCart()
    {
        $cart = new CartTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setId(self::DUMMY_2_SKU_PRODUCT_CONCRETE);
        $cartItem->setSku(self::DUMMY_2_SKU_PRODUCT_CONCRETE);
        $cartItem->setQuantity(1);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_PRODUCT_CONCRETE);
        $newItem->setSku(self::DUMMY_2_SKU_PRODUCT_CONCRETE);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return void
     */
    public function testDecreaseCartItem()
    {
        $cart = new CartTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_PRODUCT_CONCRETE);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_1_SKU_PRODUCT_CONCRETE);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->decreaseQuantity($cartChange);
        $cartItems = $changedCart->getItems();
        $this->assertCount(1, $cartItems);
        /** @var ItemTransfer $changedItem */
        $changedItem = $cartItems[0];
        $this->assertEquals(2, $changedItem->getQuantity());
    }

    /**
     * @return void
     */
    protected function setTestData()
    {
        $localeTransfer = (new LocaleFacade())->getCurrentLocale();
        $idLocale = $localeTransfer->getIdLocale();

        $defaultPriceType = SpyPriceTypeQuery::create()
            ->findOneOrCreate();

        $defaultPriceType
            ->setName(self::PRICE_TYPE_DEFAULT)
            ->save();

        $productAbstract1 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_PRODUCT_ABSTRACT)
            ->findOneOrCreate();

        $productAbstract1
            ->setAttributes('{}')
            ->save();

        $productConcrete1 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_PRODUCT_CONCRETE)
            ->findOneOrCreate();

        $productConcrete1
            ->setSpyProductAbstract($productAbstract1)
            ->setAttributes('{}')
            ->save();

        $productConcrete1LocalizedAttributes = SpyProductLocalizedAttributesQuery::create()
            ->filterByFkProduct($productConcrete1->getIdProduct())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $productConcrete1LocalizedAttributes
            ->setName('foo')
            ->setAttributes('bar')
            ->setIsComplete(1)
            ->save();

        $productAbstract2 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_PRODUCT_ABSTRACT)
            ->findOneOrCreate();

        $productAbstract2
            ->setSku(self::DUMMY_2_SKU_PRODUCT_ABSTRACT)
            ->setAttributes('{}')
            ->save();

        $productConcrete2 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_PRODUCT_CONCRETE)
            ->findOneOrCreate();

        $productConcrete2
            ->setSku(self::DUMMY_2_SKU_PRODUCT_CONCRETE)
            ->setSpyProductAbstract($productAbstract2)
            ->setAttributes('{}')
            ->save();

        $productConcrete2LocalizedAttributes = SpyProductLocalizedAttributesQuery::create()
            ->filterByFkProduct($productConcrete2->getIdProduct())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $productConcrete2LocalizedAttributes
            ->setName('foo')
            ->setAttributes('bar')
            ->setIsComplete(1)
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($productConcrete1)
            ->filterBySpyProductAbstract($productAbstract1)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($productConcrete2)
            ->filterBySpyProductAbstract($productAbstract2)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();
    }

}
