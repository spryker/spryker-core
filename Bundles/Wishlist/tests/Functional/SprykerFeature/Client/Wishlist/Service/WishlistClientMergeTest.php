<?php

namespace Functional\SprykerFeature\Client\Wishlist\Service;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Propel\Runtime\Propel;
use SprykerEngine\Zed\Kernel\InternalClassBuilderForTests;
use SprykerFeature\Client\Wishlist\Service\Action\SaveAction;
use SprykerFeature\Client\Wishlist\WishlistDependencyProvider;
use Generated\Shared\Transfer\WishlistProductTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use SprykerEngine\Client\Kernel\Container;



/**
 * @group SprykerFeature
 * @group Client
 * @group Wishlist
 * @group Service
 * @group WishlistClientTest
 */

class WishlistClientMergeTest extends Test
{
    protected $wishlistClient;
    protected $sutContainer;

    use InternalClassBuilderForTests;
    use WishlistClientTestSetup;

    public function setUp()
    {
        parent::setUp();

        $sut_container = null;

        $this->wishlistClient = $this->getWishlistClientByCustomer(function (Container $container) use (&$sut_container)
        {
            $sessionwishlistitem = new WishlistItemTransfer();
            $sessionwishlistitem->setProduct((new WishlistProductTransfer())->setAbstractSku('test2')->setConcreteSku('test11'));
            $sessionwishlistitem->setQuantity(2);
            $sessionwishlistitem->setAddedAt(time());

            $wishlistTransfer = new WishlistTransfer();
            $wishlistTransfer->setItems(new \ArrayObject([$sessionwishlistitem]));

            $container[WishlistDependencyProvider::SESSION]->set(SaveAction::getWishlistSessionID(), $wishlistTransfer);
            $sut_container = clone($container);
        });

        $this->sutContainer = $sut_container;

        $this->cleanDatabaseWishlistItemsData();

        $this->setDatabaseWishlistItemsData();
    }

    public function tearDown()
    {
        $this->sutContainer[WishlistDependencyProvider::SESSION]->set(SaveAction::getWishlistSessionID(), null);
    }



    /**
     * @group WishlistClientMergeTest
     */
    public function testMerge()
    {
        $dbWishlist = $this->wishlistClient->mergeWishlist();
        $sessionWishlist = $this->sutContainer[WishlistDependencyProvider::SESSION]->get(SaveAction::getWishlistSessionID());

        $this->assertEquals(2, count($dbWishlist->getItems()));
        $this->assertEquals(5, $dbWishlist->getItems()[0]->getQuantity());
        $this->assertEquals(1, $dbWishlist->getItems()[1]->getQuantity());
        $this->assertEquals($dbWishlist, $sessionWishlist);

    }




}
