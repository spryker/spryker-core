<?php

namespace Functional\SprykerFeature\Zed\Cart\Business;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Business\Factory as BusinessFactory;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CartTransfer;
use SprykerFeature\Zed\Cart\Business\CartDependencyContainer;
use SprykerFeature\Zed\Cart\Business\CartFacade;
use SprykerFeature\Zed\Cart\CartConfig;
use SprykerFeature\Zed\Cart\CartDependencyProvider;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceProductQuery;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyLocalizedProductAttributes;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;


/**
 * @group SprykerFeature
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

    public function setUp()
    {
        parent::setUp();

        $container = new Container();

        $dependencyProvider = new CartDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $dependencyProvider->provideCommunicationLayerDependencies($container);
        $dependencyProvider->providePersistenceLayerDependencies($container);

        $locator = Locator::getInstance();

        $factory = new BusinessFactory('Cart');

        $cartDependencyContainer = new CartDependencyContainer(
            $factory,
            $locator,
            new CartConfig(Config::getInstance(), $locator)
        );

        $mockFactory = $this->getMockCartBusinessFactory();
        $mockFactory
            ->expects($this->any())
            ->method('create')
            ->with("DependencyContainer")
            ->will($this->returnValue($cartDependencyContainer));

        $this->cartFacade = new CartFacade(
            $mockFactory,
            $locator
        );

        $this->cartFacade->setExternalDependencies($container);

        $this->priceFacade = $this->getFacade('SprykerFeature', 'Price');

        $this->setTestData();
    }

    /**
     * @return BusinessFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockCartBusinessFactory()
    {
        return $this->getMock('SprykerEngine\Zed\Kernel\Business\Factory', ['create'], ['Cart']);
    }

    public function testAddToCart()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new CartItemTransfer();
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var CartItemTransfer $item */
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

    public function testIncreaseCartQuantity()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new CartItemTransfer();
        $newItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->increaseQuantity($cartChange);
        $cartItems = $changedCart->getItems();
        $this->assertCount(2, $cartItems);

        /** @var CartItemTransfer $changedItem */
        $changedItem = $cartItems[1];
        $this->assertEquals(3, $changedItem->getQuantity());

        $changedItem = $cartItems[self::DUMMY_1_SKU_CONCRETE_PRODUCT];
        $this->assertEquals(1, $changedItem->getQuantity());
    }

    public function testRemoveFromCart()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(1);

        $cart->addItem($cartItem);

        $newItem = new CartItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
    }

    public function testDecreaseCartItem()
    {
        $cart = new CartTransfer();
        $cartItem = new CartItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);

        $cart->addItem($cartItem);

        $newItem = new CartItemTransfer();
        $newItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);

        $cartChange = new ChangeTransfer();
        $cartChange->setCart($cart);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->decreaseQuantity($cartChange);
        $cartItems = $changedCart->getItems();
        $this->assertCount(1, $cartItems);
        /** @var CartItemTransfer $changedItem */
        $changedItem = $cartItems[0];
        $this->assertEquals(2, $changedItem->getQuantity());
    }

    protected function setTestData()
    {
        $defaultPriceType = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_DEFAULT)->findOneOrCreate();
        $defaultPriceType->setName(self::PRICE_TYPE_DEFAULT)->save();

        $abstractProduct1 = SpyAbstractProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->findOne()
        ;
        if (!$abstractProduct1) {
            $abstractProduct1 = new SpyAbstractProduct();
        }
        $abstractProduct1->setSku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save()
        ;

        $concreteProduct1 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->findOne()
        ;

        if (!$concreteProduct1) {
            $concreteProduct1 = new SpyProduct();
        }
        $concreteProduct1
            ->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setSpyAbstractProduct($abstractProduct1)
            ->setAttributes('{}')
            ->save()
        ;

        $abstractProduct2 = SpyAbstractProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->findOne()
        ;

        if (!$abstractProduct2) {
            $abstractProduct2 = new SpyAbstractProduct();
        }

        $abstractProduct2
            ->setSku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save()
        ;

        $concreteProduct2 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->findOne()
        ;
        if (!$concreteProduct2) {
            $concreteProduct2 = new SpyProduct();
        }
        $concreteProduct2
            ->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->setSpyAbstractProduct($abstractProduct2)
            ->setAttributes('{}')
            ->save()
        ;

        $priceProductConcrete1 = SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct1)
            ->filterBySpyAbstractProduct($abstractProduct1)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save()
        ;

        $priceProductConcrete2 = SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct2)
            ->filterBySpyAbstractProduct($abstractProduct2)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save()
        ;
    }

}
