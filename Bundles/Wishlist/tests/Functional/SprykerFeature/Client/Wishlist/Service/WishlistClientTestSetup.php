<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Transfer\CustomerTransfer;
use Propel\Runtime\Propel;
use SprykerFeature\Client\Wishlist\Service\WishlistClient;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;
use SprykerEngine\Client\Kernel\Locator;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProductQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;
use SprykerEngine\Client\Kernel\Container;
use SprykerEngine\Client\Kernel\Service\Factory;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\Base\SpyWishlistItemQuery;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistItem;
use SprykerFeature\Zed\Wishlist\Persistence\Propel\SpyWishlistQuery;


trait WishlistClientTestSetup
{

    /**
     * @return WishlistClient
     */
    protected function getWishlistClientByCustomer(\Closure $extendContainerCallback = null)
    {
        $wishlistClient = $this->getWishlistClient();

        $container = (new WishlistDependencyProvider())
            ->provideServiceLayerDependencies(new Container());

        $this->provideCustomeMock($container);

        if (null !== $extendContainerCallback) {

            call_user_func($extendContainerCallback, $container);

        }

        $wishlistClient->setExternalDependencies($container);

        return $wishlistClient;
    }

    /**
     * @param Container $container
     */
    protected function provideCustomeMock(Container $container)
    {
        $container[WishlistDependencyProvider::CUSTOMER_CLIENT] =  function () {

            $customerMock = $this->getMockBuilder('SprykerFeature\Client\Customer\Service\CustomerClient')
                ->disableOriginalConstructor()
                ->setMethods(['getCustomer'])
                ->getMock();

            $customerTransfer = new CustomerTransfer();
            $customerTransfer->setIdCustomer(1);

            $customerMock->expects($this->any())
                ->method('getCustomer')
                ->will($this->returnValue($customerTransfer));

            return $customerMock;

        };
    }


    /**
     * @return WishlistClient
     */
    protected function getWishlistClient()
    {
        $wishlistclient = new WishlistClient(new Factory("Wishlist"), Locator::getInstance());

        $container = (new WishlistDependencyProvider())->provideServiceLayerDependencies(new Container());

        $wishlistclient->setExternalDependencies($container);

        return $wishlistclient;

    }



    public function setTestData()
    {
        $this->setCustomer();

        $this->setProduct();
    }

    protected function setCustomer()
    {
        $customer = SpyCustomerQuery::create()
            ->filterByIdCustomer(1)
            ->findOne();

        if (!$customer) {
            (new SpyCustomer())->setEmail('test@test.de')->save();
        }
    }


    protected function setProduct($sku="test1")
    {
        $abstractProduct = SpyAbstractProductQuery::create()
            ->filterBySku('test2')
            ->findOne()
        ;


        if (!$abstractProduct) {
            $abstractProduct = new SpyAbstractProduct();
        }

        $saved = $abstractProduct
            ->setSku('test2')
            ->setAttributes('{}')
            ->save()
        ;
        Propel::getConnection()->commit();



        $productEntity = SpyProductQuery::create()
            ->filterByFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->filterBySku($sku)
            ->findOne()
        ;

        if (!$productEntity) {
            $productEntity = new SpyProduct();
        }

        $productEntity
            ->setFkAbstractProduct($abstractProduct->getIdAbstractProduct())
            ->setSku($sku)
            ->setAttributes('{}')
            ->save()
        ;
        Propel::getConnection()->commit();
    }

    protected function cleanDatabaseWishlistItemsData()
    {
        SpyWishlistItemQuery::create()->deleteAll();
    }

    protected function setDatabaseWishlistItemsData()
    {
        // product test11 in wishlist
        $this->setProduct("test11");

        $productEntity = SpyProductQuery::create()
            ->filterBySku('test11')
            ->findOne();

        $wishlistEntity = SpyWishlistQuery::create()
            ->filterByFkCustomer(1)
            ->findOne();

        $wishlistItemEntity = new SpyWishlistItem;

        $wishlistItemEntity->setAddedAt(time())
            ->setQuantity(3)
            ->setFkConcreteProduct($productEntity->getIdProduct())
            ->setFkWishlist($wishlistEntity->getIdWishlist())
            ->save();

        // product test2 in wishlist
        $this->setProduct("test12");

        $productEntity = SpyProductQuery::create()
            ->filterBySku('test12')
            ->findOne();

        $wishlistEntity = SpyWishlistQuery::create()
            ->filterByFkCustomer(1)
            ->findOne();

        $wishlistItemEntity = new SpyWishlistItem;

        $wishlistItemEntity->setAddedAt(time())
            ->setQuantity(1)
            ->setFkConcreteProduct($productEntity->getIdProduct())
            ->setFkWishlist($wishlistEntity->getIdWishlist())
            ->save();

    }


}
