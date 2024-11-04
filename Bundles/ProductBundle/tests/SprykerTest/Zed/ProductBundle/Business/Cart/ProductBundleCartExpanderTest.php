<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Price\PriceReaderInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Spryker\Zed\ProductBundle\ProductBundleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Cart
 * @group ProductBundleCartExpanderTest
 * Add your own group annotations below this line
 */
class ProductBundleCartExpanderTest extends Unit
{
    /**
     * @var array
     */
    protected $fixtures = [
        'idProductConcrete' => 1,
        'bundledProductSku' => 'sku-123',
        'fkBundledProduct' => 2,
        'bundledProductQuantity' => 2,
        'idProductBundle' => 1,
    ];

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldExtractBundledItemsAndDistributeBundlePrice(): void
    {
        $productExpanderMock = $this->setupProductExpander();

        $this->setupFindBundledItemsByIdProductConcrete($this->fixtures, $productExpanderMock);

        $cartChangeTransfer = $this->createCartChangeTransfer();

        $updatedCartChangeTransfer = $productExpanderMock->expandBundleItems($cartChangeTransfer);

        $bundleItems = $updatedCartChangeTransfer->getQuote()->getBundleItems();
        $updatedAddToCartItems = $updatedCartChangeTransfer->getItems();

        $this->assertCount(4, $updatedAddToCartItems);
        $this->assertCount(2, $bundleItems);
        $this->assertCount(0, $updatedCartChangeTransfer->getQuote()->getItems());

        $bundleItemTransfer = $bundleItems[0];

        $bundledItemTransfer = $updatedAddToCartItems[0];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertSame($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundledItemTransfer = $updatedAddToCartItems[1];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertSame($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundleItemTransfer = $bundleItems[1];

        $bundledItemTransfer = $updatedAddToCartItems[2];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertSame($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundledItemTransfer = $updatedAddToCartItems[3];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1, $bundledItemTransfer->getQuantity());
        $this->assertSame($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());
    }

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldCopyShipmentMethodFromBundleItemToBundledItems(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentTransfer())->setIdShipmentMethod(1234);

        $productBundleConfigMock = Stub::makeEmpty(
            ProductBundleConfig::class,
            [
                'getAllowedBundleItemFieldsToCopy' => [ItemTransfer::SHIPMENT],
            ],
        );

        $productExpanderMock = $this->setupProductExpander($productBundleConfigMock);

        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->getItems()->getIterator()->current()->setShipment($shipmentTransfer);

        // Act
        $cartChangeTransfer = $productExpanderMock->expandBundleItems($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $shipmentTransfer->getIdShipmentMethod(),
            $cartChangeTransfer->getItems()->offsetGet(0)->getShipment()->getIdShipmentMethod(),
        );
        $this->assertSame(
            $shipmentTransfer->getIdShipmentMethod(),
            $cartChangeTransfer->getItems()->offsetGet(1)->getShipment()->getIdShipmentMethod(),
        );
        $this->assertSame(
            $shipmentTransfer->getIdShipmentMethod(),
            $cartChangeTransfer->getItems()->offsetGet(2)->getShipment()->getIdShipmentMethod(),
        );
        $this->assertSame(
            $shipmentTransfer->getIdShipmentMethod(),
            $cartChangeTransfer->getItems()->offsetGet(3)->getShipment()->getIdShipmentMethod(),
        );
    }

    /**
     * @return void
     */
    public function testExpandBundleItemsShouldSkipCopyFromBundleItemToBundledItems(): void
    {
        // Arrange
        $shipmentTransfer = (new ShipmentTransfer())->setIdShipmentMethod(1234);
        $productExpanderMock = $this->setupProductExpander();

        $cartChangeTransfer = $this->createCartChangeTransfer();
        $cartChangeTransfer->getItems()->getIterator()->current()->setShipment($shipmentTransfer);

        // Act
        $cartChangeTransfer = $productExpanderMock->expandBundleItems($cartChangeTransfer);

        // Assert
        $this->assertNull($cartChangeTransfer->getItems()->offsetGet(0)->getShipment());
        $this->assertNull($cartChangeTransfer->getItems()->offsetGet(1)->getShipment());
        $this->assertNull($cartChangeTransfer->getItems()->offsetGet(2)->getShipment());
        $this->assertNull($cartChangeTransfer->getItems()->offsetGet(3)->getShipment());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface|null $priceProductFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface|null $productFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface|null $localeFacadeMock
     * @param \Spryker\Zed\ProductBundle\ProductBundleConfig|null $productBundleConfigMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    protected function createProductExpanderMock(
        ?ProductBundleToPriceProductFacadeInterface $priceProductFacadeMock = null,
        ?ProductBundleToProductFacadeInterface $productFacadeMock = null,
        ?ProductBundleToLocaleFacadeInterface $localeFacadeMock = null,
        ?ProductBundleConfig $productBundleConfigMock = null
    ): ProductBundleCartExpander {
        if ($priceProductFacadeMock === null) {
            $priceProductFacadeMock = $this->createPriceProductFacadeMock();
        }

        if ($productFacadeMock === null) {
            $productFacadeMock = $this->createProductFacadeMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        if ($productBundleConfigMock === null) {
            $productBundleConfigMock = Stub::makeEmpty(ProductBundleConfig::class);
        }

        $priceReaderMock = $this->createPriceReaderMock();

        $productBundleReaderMock = $this->createProductBundleReaderMock();

        return $this->getMockBuilder(ProductBundleCartExpander::class)
            ->setConstructorArgs([
                $priceProductFacadeMock,
                $productFacadeMock,
                $localeFacadeMock,
                $productBundleReaderMock,
                $priceReaderMock,
                $productBundleConfigMock,
            ])
            ->addMethods(['findBundledItemsByIdProductConcrete'])
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\ProductBundle\ProductBundleConfig|null $productBundleConfigMock
     *
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    protected function setupProductExpander(?ProductBundleConfig $productBundleConfigMock = null): ProductBundleCartExpander
    {
        $productFacadeMock = $this->createProductFacadeMock();

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productFacadeMock->method('getProductConcrete')->willReturn($productConcreteTransfer);
        $productFacadeMock->method('getLocalizedProductConcreteName')->willReturn('Localized product name');

        $localeFacadeMock = $this->createLocaleFacadeMock();

        $localeTransfer = new LocaleTransfer();
        $localeFacadeMock->method('getCurrentLocale')->willReturn($localeTransfer);

        $priceFacadeMock = $this->createPriceProductFacadeMock();
        $priceFacadeMock->method('findPriceFor')->willReturn(25);

        $productExpanderMock = $this->createProductExpanderMock(
            $priceFacadeMock,
            $productFacadeMock,
            $localeFacadeMock,
            $productBundleConfigMock,
        );

        return $productExpanderMock;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransfer(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPriceMode(PriceConfig::PRICE_MODE_GROSS);

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');

        $storeTransfer = new StoreTransfer();
        $storeTransfer->setName('DE');

        $quoteTransfer->setCurrency($currencyTransfer);
        $quoteTransfer->setStore($storeTransfer);
        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(2);
        $itemTransfer->setUnitGrossPrice(300);
        $itemTransfer->setUnitPrice(300);
        $itemTransfer->setUnitNetPrice(300);
        $itemTransfer->setSku('sku-123');
        $itemTransfer->setId(1);

        $cartChangeTransfer->addItem($itemTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param array $fixtures
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander $productExpanderMock
     *
     * @return void
     */
    protected function setupFindBundledItemsByIdProductConcrete(
        array $fixtures,
        ProductBundleCartExpander $productExpanderMock
    ): void {
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setIdProductBundle($fixtures['idProductConcrete']);
        $productBundleEntity->setQuantity($fixtures['bundledProductQuantity']);

        $productEntity = new SpyProduct();
        $productEntity->setIdProduct($fixtures['fkBundledProduct']);
        $productEntity->setSku($fixtures['bundledProductSku']);

        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);
        $productBundleEntity->setFkBundledProduct($fixtures['fkBundledProduct']);

        $bundledProducts = new ObjectCollection();
        $bundledProducts->append($productBundleEntity);

        $productExpanderMock->method('findBundledItemsByIdProductConcrete')->willReturn($bundledProducts);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock(): ProductBundleQueryContainerInterface
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface
     */
    protected function createPriceProductFacadeMock(): ProductBundleToPriceProductFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToPriceProductFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeInterface
     */
    protected function createProductFacadeMock(): ProductBundleToProductFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToProductFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeInterface
     */
    protected function createLocaleFacadeMock(): ProductBundleToLocaleFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToLocaleFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Price\PriceReaderInterface
     */
    protected function createPriceReaderMock(): PriceReaderInterface
    {
        return $this->getMockBuilder(PriceReaderInterface::class)->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer
     */
    protected function createProductForBundleTransfer(): ProductForBundleTransfer
    {
        return (new ProductForBundleTransfer())
            ->setSku($this->fixtures['bundledProductSku'])
            ->setQuantity($this->fixtures['bundledProductQuantity'])
            ->setIsActive(true);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected function createProductBundleReaderMock(): ProductBundleReaderInterface
    {
        $productBundleReaderMock = $this->getMockBuilder(ProductBundleReaderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productBundleReaderMock->method('getProductForBundleTransfersByProductConcreteSkus')
            ->willReturn([$this->fixtures['bundledProductSku'] => [$this->createProductForBundleTransfer()]]);

        return $productBundleReaderMock;
    }
}
