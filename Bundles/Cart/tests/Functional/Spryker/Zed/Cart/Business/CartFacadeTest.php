<?php

namespace Functional\Spryker\Zed\Cart\Business;

use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\AbstractFunctionalTest;
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
class CartFacadeTest extends AbstractFunctionalTest
{

    const PRICE_TYPE_DEFAULT = 'DEFAULT';
    const DUMMY_1_SKU_ABSTRACT_PRODUCT = 'ABSTRACT1';
    const DUMMY_1_SKU_CONCRETE_PRODUCT = 'CONCRETE1';

    const DUMMY_2_SKU_ABSTRACT_PRODUCT = 'ABSTRACT2';
    const DUMMY_2_SKU_CONCRETE_PRODUCT = 'CONCRETE2';

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

        $container = new Container();

        $dependencyProvider = new CartDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);

        $this->cartFacade = new CartFacade();
        $this->cartFacade->setExternalDependencies($container);

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
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
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
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
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

        /** @TODO check key names */
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
        $cartItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(1);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
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
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
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

        $abstractProduct1 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate();

        $abstractProduct1
            ->setAttributes('{}')
            ->save();

        $concreteProduct1 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->findOneOrCreate();

        $concreteProduct1
            ->setSpyProductAbstract($abstractProduct1)
            ->setAttributes('{}')
            ->save();

        $concreteProduct1LocalizedAttributes = SpyProductLocalizedAttributesQuery::create()
            ->filterByFkProduct($concreteProduct1->getIdProduct())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $concreteProduct1LocalizedAttributes
            ->setName('foo')
            ->setAttributes('bar')
            ->setIsComplete(1)
            ->save();

        $abstractProduct2 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate();

        $abstractProduct2
            ->setSku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save();

        $concreteProduct2 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->findOneOrCreate();

        $concreteProduct2
            ->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->setSpyProductAbstract($abstractProduct2)
            ->setAttributes('{}')
            ->save();

        $concreteProduct2LocalizedAttributes = SpyProductLocalizedAttributesQuery::create()
            ->filterByFkProduct($concreteProduct2->getIdProduct())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $concreteProduct2LocalizedAttributes
            ->setName('foo')
            ->setAttributes('bar')
            ->setIsComplete(1)
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct1)
            ->filterBySpyProductAbstract($abstractProduct1)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct2)
            ->filterBySpyProductAbstract($abstractProduct2)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();
    }

}
