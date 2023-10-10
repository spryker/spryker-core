<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Client\ClickAndCollectExample\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Client\ClickAndCollectExample\ClickAndCollectExampleDependencyProvider;
use Spryker\Client\ClickAndCollectExample\Dependency\Client\ClickAndCollectExampleToProductOfferStorageClientInterface;
use SprykerTest\Client\ClickAndCollectExample\ClickAndCollectExampleClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ClickAndCollectExample
 * @group Client
 * @group FilterUnavailableProductOfferShipmentTypesTest
 * Add your own group annotations below this line
 */
class FilterUnavailableProductOfferShipmentTypesTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU_1 = 'fake_sku_1';

    /**
     * @var string
     */
    protected const FAKE_SKU_2 = 'fake_sku_2';

    /**
     * @var string
     */
    protected const FAKE_SKU_3 = 'fake_sku_3';

    /**
     * @var string
     */
    protected const FAKE_DELIVERY = 'fake_delivery';

    /**
     * @var string
     */
    protected const FAKE_PICKUP = 'fake_pickup';

    /**
     * @var \SprykerTest\Client\ClickAndCollectExample\ClickAndCollectExampleClientTester
     */
    protected ClickAndCollectExampleClientTester $tester;

    /**
     * @return void
     */
    public function testFiltersOutShipmentTypesWithoutProductOfferShipmentTypes(): void
    {
        // Arrange
        $this->tester->setDependency(
            ClickAndCollectExampleDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock(new ProductOfferStorageCollectionTransfer()),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(0, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testFiltersOutOnlyPickupShipmentType(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_2)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY)));

        $this->tester->setDependency(
            ClickAndCollectExampleDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(1, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
        $this->assertSame(static::FAKE_DELIVERY, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages()->offsetGet(0)->getKey());
    }

    /**
     * @return void
     */
    public function testFiltersOutNothingWhenOneOfferContainsBothShipmentTypes(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_2)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY))
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_PICKUP)));

        $this->tester->setDependency(
            ClickAndCollectExampleDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(2, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testFiltersOutNothingWhenOffersContainsBothShipmentTypes(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_1)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY)))
            ->addProductOffer((new ProductOfferStorageTransfer())
                ->setProductConcreteSku(static::FAKE_SKU_2)
                ->addShipmentType((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_PICKUP)));

        $this->tester->setDependency(
            ClickAndCollectExampleDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(2, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return void
     */
    public function testFiltersOutShipmentTypesWithoutRelations(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer((new ProductOfferStorageTransfer())->setProductConcreteSku(static::FAKE_SKU_1))
            ->addProductOffer((new ProductOfferStorageTransfer())->setProductConcreteSku(static::FAKE_SKU_2))
            ->addProductOffer((new ProductOfferStorageTransfer())->setProductConcreteSku(static::FAKE_SKU_3));

        $this->tester->setDependency(
            ClickAndCollectExampleDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE,
            $this->createProductOfferStorageClientMock($productOfferStorageCollectionTransfer),
        );

        // Act
        $shipmentTypeStorageCollectionTransfer = $this->tester->getClient()->filterUnavailableProductOfferShipmentTypes(
            $this->createShipmentTypeStorageCollection(),
            $this->createQuote(),
        );

        // Assert
        $this->assertCount(0, $shipmentTypeStorageCollectionTransfer->getShipmentTypeStorages());
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer
     */
    protected function createShipmentTypeStorageCollection(): ShipmentTypeStorageCollectionTransfer
    {
        return (new ShipmentTypeStorageCollectionTransfer())
            ->addShipmentTypeStorage((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_DELIVERY))
            ->addShipmentTypeStorage((new ShipmentTypeStorageTransfer())->setKey(static::FAKE_PICKUP));
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuote(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setSku(static::FAKE_SKU_3));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Spryker\Client\ClickAndCollectExample\Dependency\Client\ClickAndCollectExampleToProductOfferStorageClientInterface
     */
    protected function createProductOfferStorageClientMock(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): ClickAndCollectExampleToProductOfferStorageClientInterface {
        $productOfferStorageClientMock = $this
            ->getMockBuilder(ClickAndCollectExampleToProductOfferStorageClientInterface::class)
            ->getMock();

        $productOfferStorageClientMock
            ->method('getProductOfferStoragesBySkus')
            ->willReturn($productOfferStorageCollectionTransfer);

        return $productOfferStorageClientMock;
    }
}
