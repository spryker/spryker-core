<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Cart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use PHPUnit\Framework\MockObject\MockObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface;
use Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceBridge;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
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
     * @var \SprykerTest\Zed\ProductBundle\ProductBundleBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider expandBundleItemsShouldCreateBundledItemsWithExpectedQuantitiesDataProvider
     *
     * @param array $quantityCollection
     * @param array $expectedQuantitiesPerItem
     * @param int $expectedItemCount
     * @param int $bundleQuantity
     *
     * @return void
     */
    public function testExpandBundleItemsShouldCreateBundledItemsWithExpectedQuantities(
        array $quantityCollection,
        array $expectedQuantitiesPerItem,
        int $expectedItemCount,
        int $bundleQuantity
    ): void {
        $cartChangeTransfer = new CartChangeTransfer();
        $bundle = $this->tester->haveProduct();

        $itemTransfer = (new ItemBuilder())->seed([
            ItemTransfer::ID => $bundle->getIdProductConcrete(),
            ItemTransfer::QUANTITY => $bundleQuantity,
            ItemTransfer::SKU => $bundle->getSku(),
        ])
            ->build();

        $storeTransfer = $this->tester->haveStore([
            'name' => 'DE',
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withCurrency()
            ->withStore([
                StoreTransfer::NAME => $storeTransfer->getName(),
            ])
            ->build();
        $quoteTransfer->addItem($itemTransfer);

        foreach ($quantityCollection as $sku => $quantity) {
            $productConcrete = $this->tester->haveProduct([
                'sku' => $sku,
            ]);
            $this->tester->createProductBundle([
                ProductForBundleTransfer::ID_PRODUCT_CONCRETE => $bundle->getIdProductConcrete(),
                ProductForBundleTransfer::ID_PRODUCT_BUNDLE => $productConcrete->getIdProductConcrete(),
                ProductForBundleTransfer::QUANTITY => $quantity,
            ]);
        }
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->addItem($itemTransfer);

        $resultCartChangeTransfer = $this->tester->getFacade()->expandBundleItems($cartChangeTransfer);

        $this->assertCount($expectedItemCount, $cartChangeTransfer->getItems(), 'Item count mismatch');

        $groupedItemsBySku = [];

        foreach ($resultCartChangeTransfer->getItems() as $itemTransfer) {
            $groupedItemsBySku[$itemTransfer->getSku()][] = $itemTransfer;
        }

        foreach ($groupedItemsBySku as $sku => $itemTransfers) {
            foreach ($itemTransfers as $key => $itemTransfer) {
                $this->assertEquals($expectedQuantitiesPerItem[$sku][$key], $itemTransfer->getQuantity());
            }
        }
    }

    /**
     * @return array
     */
    public function expandBundleItemsShouldCreateBundledItemsWithExpectedQuantitiesDataProvider(): array
    {

        return [
            'int stock' => [
                [
                    'foo' => 1,
                    'bar' => 2,
                ],
                [
                    'foo' => [1],
                    'bar' => [1, 1],
                ],
                3,
                1,
            ],
            'float stock' => [
                [
                    'foo' => 1,
                    'bar' => 2.5,
                    'baz' => 0.6,
                ],
                [
                    'foo' => [1],
                    'bar' => [1, 1, 0.5],
                    'baz' => [0.6],
                ],
                5,
                1,
            ],
            'float stock 2 bundles' => [
                [
                    'foo' => 1,
                    'bar' => 2.5,
                    'baz' => 0.6,
                ],
                [
                    'foo' => [1, 1],
                    'bar' => [1, 1, 0.5, 1, 1, 0.5],
                    'baz' => [0.6, 0.6],
                ],
                10,
                2,
            ],
        ];
    }

    /**
     * @dataProvider cartChangeTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param int $expectedItemsCount
     * @param int $expectedBundleItemsCount
     *
     * @return void
     */
    public function testExpandBundleItemsShouldExtractBundledItemsAndDistributeBundlePrice(
        CartChangeTransfer $cartChangeTransfer,
        int $expectedItemsCount,
        int $expectedBundleItemsCount
    ) {
        $productExpanderMock = $this->setupProductExpander();

        $this->setupFindBundledItemsByIdProductConcrete($this->fixtures, $productExpanderMock);

        $updatedCartChangeTransfer = $productExpanderMock->expandBundleItems($cartChangeTransfer);

        $bundleItems = $updatedCartChangeTransfer->getQuote()->getBundleItems();
        $updatedAddToCartItems = $updatedCartChangeTransfer->getItems();

        $this->assertCount($expectedItemsCount, $updatedAddToCartItems);
        $this->assertCount($expectedBundleItemsCount, $bundleItems);
        $this->assertCount(0, $updatedCartChangeTransfer->getQuote()->getItems());

        $bundleItemTransfer = $bundleItems[0];

        $bundledItemTransfer = $updatedAddToCartItems[0];

        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1.0, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundledItemTransfer = $updatedAddToCartItems[1];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1.0, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundleItemTransfer = $bundleItems[1];

        $bundledItemTransfer = $updatedAddToCartItems[2];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1.0, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());

        $bundledItemTransfer = $updatedAddToCartItems[3];
        $this->assertSame(150, $bundledItemTransfer->getUnitGrossPrice());
        $this->assertSame(1.0, $bundledItemTransfer->getQuantity());
        $this->assertEquals($bundleItemTransfer->getBundleItemIdentifier(), $bundledItemTransfer->getRelatedBundleItemIdentifier());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface|null $priceProductFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface|null $productFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface|null $localeFacadeMock
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface|null $productBundleQueryContainerMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    protected function createProductExpanderMock(
        ?ProductBundleToPriceProductFacadeInterface $priceProductFacadeMock = null,
        ?ProductBundleToProductInterface $productFacadeMock = null,
        ?ProductBundleToLocaleInterface $localeFacadeMock = null,
        ?ProductBundleQueryContainerInterface $productBundleQueryContainerMock = null
    ) {

        if ($productBundleQueryContainerMock === null) {
            $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();
        }

        if ($priceProductFacadeMock === null) {
            $priceProductFacadeMock = $this->createPriceProductFacadeMock();
        }

        if ($productFacadeMock === null) {
            $productFacadeMock = $this->createProductFacadeMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleFacadeMock();
        }

        $priceFacadeMock = $this->createPriceFacadeMock();
        $utilQuantityService = new ProductBundleToUtilQuantityServiceBridge(
            $this->tester->getLocator()->utilQuantity()->service()
        );

        return $this->getMockBuilder(ProductBundleCartExpander::class)
            ->setConstructorArgs([
                $productBundleQueryContainerMock,
                $priceProductFacadeMock,
                $productFacadeMock,
                $localeFacadeMock,
                $priceFacadeMock,
                $utilQuantityService,
            ])
            ->setMethods(['findBundledItemsByIdProductConcrete'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander
     */
    protected function setupProductExpander()
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

        $productExpanderMock = $this->createProductExpanderMock($priceFacadeMock, $productFacadeMock, $localeFacadeMock);

        return $productExpanderMock;
    }

    /**
     * @return array
     */
    public function cartChangeTransferDataProvider(): array
    {
        $cartChangeTransfer = [
            'int quantity' => $this->getDataForCartChangeTransfer(
                2,
                $this->fixtures['bundledProductSku'],
                4,
                2
            ),
            'float quantity' => $this->getDataForCartChangeTransfer(
                2.1,
                $this->fixtures['bundledProductSku'],
                6,
                3
            ),
        ];

        return $cartChangeTransfer;
    }

    /**
     * @param float|int $quantity
     * @param string $bundleSku
     * @param int $expectedItemsCount
     * @param int $expectedBundleItemsCount
     *
     * @return array
     */
    protected function getDataForCartChangeTransfer(
        $quantity,
        string $bundleSku,
        int $expectedItemsCount,
        int $expectedBundleItemsCount
    ): array {
        $currencyTransfer = (new CurrencyTransfer())->setCode('EUR');
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(PriceConfig::PRICE_MODE_GROSS)
            ->setCurrency($currencyTransfer)
            ->setStore((new StoreTransfer())->setName('DE'));

        $itemTransfer = (new ItemTransfer())
            ->setQuantity($quantity)
            ->setUnitGrossPrice(300)
            ->setUnitPrice(300)
            ->setUnitNetPrice(300)
            ->setSku($bundleSku)
            ->setId(1);

        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem($itemTransfer);

        return [$cartChangeTransfer, $expectedItemsCount, $expectedBundleItemsCount];
    }

    /**
     * @param array $fixtures
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Cart\ProductBundleCartExpander $productExpanderMock
     *
     * @return void
     */
    protected function setupFindBundledItemsByIdProductConcrete(
        array $fixtures,
        MockObject $productExpanderMock
    ) {

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
    protected function createProductBundleQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeInterface
     */
    protected function createPriceProductFacadeMock()
    {
         return $this->getMockBuilder(ProductBundleToPriceProductFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductInterface
     */
    protected function createProductFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToProductInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleInterface
     */
    protected function createLocaleFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToLocaleInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceInterface
     */
    protected function createPriceFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToPriceInterface::class)->getMock();
    }
}
