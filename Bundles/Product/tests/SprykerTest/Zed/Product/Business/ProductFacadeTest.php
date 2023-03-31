<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCreatedTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductDeletedTransfer;
use Generated\Shared\Transfer\ProductExportCriteriaTransfer;
use Generated\Shared\Transfer\ProductExportedTransfer;
use Generated\Shared\Transfer\ProductPublisherConfigTransfer;
use Generated\Shared\Transfer\ProductUpdatedTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Business\Exception\ProductPublisherEventNameMismatchException;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Product\ProductDependencyProvider;
use Spryker\Zed\Store\StoreDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group ProductFacadeTest
 * Add your own group annotations below this line
 */
class ProductFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @var array<string, string>
     */
    protected const PRODUCT_NAME = [
        self::LOCALE_NAME_EN => 'Product name en_US',
        self::LOCALE_NAME_DE => 'Product name de_DE',
    ];

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_NAME_EN = 'en_US';

    /**
     * @var string
     */
    protected const SKU_1 = 'test-sku1';

    /**
     * @var string
     */
    protected const SKU_2 = 'test-sku2';

    /**
     * @var string
     */
    protected const LOCALIZED_ATTRIBUTE_NAME = 'name';

    /**
     * @var string
     */
    protected const UNEXISTING_STORE_REFERENCE = 'store-doesnt-exists';

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterfrace
     */
    protected $messageBrokerFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface
     */
    protected $eventFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setUpDatabase();

        $this->messageBrokerFacade = $this->createMock(ProductToMessageBrokerInterfrace::class);

        $this->tester->setDependency(
            ProductDependencyProvider::FACADE_MESSAGE_BROKER,
            $this->messageBrokerFacade,
        );

        $this->eventFacade = $this->createMock(ProductToEventInterface::class);

        $this->tester->setDependency(
            ProductDependencyProvider::FACADE_EVENT,
            $this->eventFacade,
        );

        $this->tester->setStoreReferenceData([
            'DE' => 'dev-DE',
            'AT' => 'dev-AT',
        ]);
    }

    /**
     * @return void
     */
    public function testGenerateProductConcreteSku(): void
    {
        $sku = $this->tester->getFacade()->generateProductConcreteSku(
            $this->createProductAbstractTransfer(),
            $this->createProductConcreteTransfer(),
        );

        $this->assertSame($this->getExpectedProductConcreteSku(), $sku);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteTransfersByProductIdsRetrievesAllSpecifiedProductconcreteAsTransferWithId(): void
    {
        $productConcreteIds = $this->tester->getProductConcreteIds();

        $this->assertTrue(count($productConcreteIds) > 0);
        $productConcreteTransfers = $this->tester->getProductFacade()->getProductConcreteTransfersByProductIds($productConcreteIds);
        $this->assertSame(count($productConcreteIds), count($productConcreteTransfers));

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertContains($productConcreteTransfer->getIdProductConcrete(), $productConcreteIds);
        }
    }

    /**
     * @return void
     */
    public function testGetProductConcreteTransfersByProductAbstractIds(): void
    {
        $productAbstractIds = $this->tester->getProductAbstractIds();

        $this->assertTrue(count($productAbstractIds) > 0);
        $productConcreteTransfers = $this->tester->getProductFacade()->getProductConcreteTransfersByProductAbstractIds($productAbstractIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertContains($productConcreteTransfer->getFkProductAbstract(), $productAbstractIds);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(): ProductAbstractTransfer
    {
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('abstract_sku');

        return $productAbstractTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(): ProductConcreteTransfer
    {
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setAttributes([
            'processor_frequency' => '4 GHz',
            'processor_cache' => '12 MB',
        ]);

        return $productConcreteTransfer;
    }

    /**
     * @return string
     */
    protected function getExpectedProductConcreteSku(): string
    {
        return 'abstract_sku' .
            SkuGenerator::SKU_ABSTRACT_SEPARATOR .
            'processor_frequency' .
            SkuGenerator::SKU_TYPE_SEPARATOR .
            '4GHz' .
            SkuGenerator::SKU_VALUE_SEPARATOR .
            'processor_cache' .
            SkuGenerator::SKU_TYPE_SEPARATOR .
            '12MB';
    }

    /**
     * @return void
     */
    public function testGetProductUrlsByOneProductAbstractIdAndLocale(): void
    {
        // Arrange
        $this->tester->createProductUrls();

        $idProductAbstract = $this->tester->getProductAbstractIds()[0];

        $productUrlCriteriaFilterTransfer = (new ProductUrlCriteriaFilterTransfer())
            ->setProductAbstractIds([$idProductAbstract])
            ->setIdLocale($this->tester->getLocaleFacade()->getCurrentLocale()->getIdLocale());

        $correctUrl = $this->tester->getProductUrl($idProductAbstract, $this->tester->getLocaleFacade()->getCurrentLocale()->getLocaleName());

        // Act
        $productUrls = $this->tester->getProductFacade()->getProductUrls($productUrlCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $productUrls);
        $this->assertSame($correctUrl, $productUrls[0]->getUrl());
    }

    /**
     * @return void
     */
    public function testGetProductUrlsByLocaleAndWithoutProductAbstractIds(): void
    {
        // Arrange
        $idLocale = $this->tester->getLocaleFacade()->getCurrentLocale()->getIdLocale();
        $this->tester->createProductUrls();

        $expectedProductUrlsCount = $this->tester->getUrlsCount($idLocale);
        $productUrlCriteriaFilterTransfer = (new ProductUrlCriteriaFilterTransfer())->setIdLocale($idLocale);

        // Act
        $productUrls = $this->tester->getProductFacade()->getProductUrls($productUrlCriteriaFilterTransfer);

        // Assert
        $this->assertCount($expectedProductUrlsCount, $productUrls);
    }

    /**
     * @return void
     */
    public function testGetProductUrlsByProductAbstractIdAndWithoutLocale(): void
    {
        // Arrange
        $this->tester->createProductUrls();

        $productUrlCriteriaFilterTransfer = (new ProductUrlCriteriaFilterTransfer())
            ->setProductAbstractIds([$this->tester->getProductAbstractIds()[0]]);

        // Act
        $productUrls = $this->tester->getProductFacade()->getProductUrls($productUrlCriteriaFilterTransfer);

        // Assert
        $this->assertCount(2, $productUrls);
    }

    /**
     * @return void
     */
    public function testGetProductConcretesByCriteria(): void
    {
        // Arrange
        $productConcreteIds = $this->tester->getProductConcreteIds();
        $productConcreteTransfer = $this->tester->getFacade()->findProductConcreteById($productConcreteIds[0]);
        $productCriteriaTransferWithExistingStore = new ProductCriteriaTransfer();
        $productCriteriaTransferWithExistingStore->setIdStore(
            $this->tester->getStoreFacade()->getCurrentStore()->getIdStore(),
        );
        $productCriteriaTransferWithExistingStore->setIsActive(true);
        $productCriteriaTransferWithExistingStore->setSkus([$productConcreteTransfer->getSku()]);

        $productCriteriaTransferWithNotExistingStore = clone $productCriteriaTransferWithExistingStore;
        $productCriteriaTransferWithNotExistingStore->setIdStore(9999);

        // Act
        $productConcreteTransfersWithStore = $this->tester->getFacade()->getProductConcretesByCriteria($productCriteriaTransferWithExistingStore);
        $productConcreteTransfersWithoutStore = $this->tester->getFacade()->getProductConcretesByCriteria($productCriteriaTransferWithNotExistingStore);

        // Assert
        $this->assertCount(1, $productConcreteTransfersWithStore);
        $this->assertCount(0, $productConcreteTransfersWithoutStore);
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteCollectionCreatesConcreteProducts(): void
    {
        // Arrange
        $this->tester->deleteConcreteProductBySkus([static::SKU_1, static::SKU_2]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $expectedProductsNumber = $this->tester->getProductConcreteDatabaseEntriesCount() + 2;
        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(static::SKU_1)
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
            )->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(static::SKU_2)
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
            );

        // Act
        $this->tester->getFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);

        // Assert
        $this->assertEquals($expectedProductsNumber, $this->tester->getProductConcreteDatabaseEntriesCount());
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteCollectionCreatesLocalizedAttributes(): void
    {
        // Arrange
        $localeTransfer1 = $this->tester->haveLocale();
        $localeTransfer2 = $this->tester->haveLocale();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $expectedLocalizedAttributesNumber = $this->tester->countProductLocalizedAttributesByProductBySkus([static::SKU_1]) + 2;
        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(static::SKU_1)
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail())
                    ->addLocalizedAttributes(
                        (new LocalizedAttributesTransfer())->setLocale($localeTransfer1)->setName(static::LOCALIZED_ATTRIBUTE_NAME),
                    )->addLocalizedAttributes(
                        (new LocalizedAttributesTransfer())->setLocale($localeTransfer2)->setName(static::LOCALIZED_ATTRIBUTE_NAME),
                    ),
            );

        // Act
        $this->tester->getFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);

        // Assert
        $this->assertEquals(
            $expectedLocalizedAttributesNumber,
            $this->tester->countProductLocalizedAttributesByProductBySkus([static::SKU_1]),
        );
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteCollectionThrowsExceptionIfConcreteProductWithTheSameSkuExists(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku($productConcreteTransfer->getSku())
                    ->setFkProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail()),
            );

        // Assert
        $this->expectException(ProductConcreteExistsException::class);

        // Act
        $this->tester->getFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstractShouldReturnProductAbstractNamesByGivenIds(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $localeTransfers = $this->getLocales();

        $localizedAttributeEN = $this->createLocalizedAttributesTransfer(static::PRODUCT_NAME[static::LOCALE_NAME_EN], $localeTransfers[static::LOCALE_NAME_EN]);
        $localizedAttributeDE = $this->createLocalizedAttributesTransfer(static::PRODUCT_NAME[static::LOCALE_NAME_DE], $localeTransfers[static::LOCALE_NAME_DE]);

        $this->tester->addLocalizedAttributesToProductAbstract($productAbstractTransfer1, [$localizedAttributeEN]);
        $this->tester->addLocalizedAttributesToProductAbstract($productAbstractTransfer2, [$localizedAttributeDE]);

        $expectedProductAbstractLocalizedAttributeNames = [];
        $expectedProductAbstractLocalizedAttributeNames[$productAbstractTransfer1->getIdProductAbstract()] = static::PRODUCT_NAME[static::LOCALE_NAME_EN];
        $expectedProductAbstractLocalizedAttributeNames[$productAbstractTransfer2->getIdProductAbstract()] = static::PRODUCT_NAME[static::LOCALE_NAME_DE];

        // Act
        $actualProductAbstractLocalizedAttributeNames = $this->tester->getFacade()->getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract([
            $productAbstractTransfer1->getIdProductAbstract(), $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        // Assert
        $this->assertEquals($expectedProductAbstractLocalizedAttributeNames, $actualProductAbstractLocalizedAttributeNames);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstractShouldReturnEmptyArrayWhenProductAbstractDoesNotHaveLocalizedAttributes(): void
    {
        // Arrange
        $this->tester->ensureProductAbstractTableIsEmpty();

        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        // Act
        $productAbstractLocalizedAttributeNames = $this->tester->getFacade()->getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract([
            $productAbstractTransfer1->getIdProductAbstract(), $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        // Assert
        $this->assertEmpty($productAbstractLocalizedAttributeNames);
    }

    /**
     * @return void
     */
    public function testProductsNotPublishedToMessageBrokerIfProductIdsIsEmpty(): void
    {
        // Arrange
        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setEventName(ProductExportedTransfer::class);

        // Assert
        $this->messageBrokerFacade
            ->expects($this->never())
            ->method('sendMessage');

        // Act
        $this->tester->getProductFacade()->emitPublishProductToMessageBroker($productPublisherConfigTransfer);
    }

    /**
     * @return void
     */
    public function testProductsSuccessfullyPublishedToMessageBroker(): void
    {
        // Arrange
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);
        $concreteProduct = $this->tester->haveFullProduct();

        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setProductIds([$concreteProduct->getIdProductConcrete()])
            ->setEventName(ProductExportedTransfer::class);

        // Assert
        $this->tester->assertProductSuccessfullyPublishedViaMessageBroker(
            $this->messageBrokerFacade,
            $concreteProduct,
            ProductExportedTransfer::class,
        );

        // Act
        $this->tester->getProductFacade()->emitPublishProductToMessageBroker($productPublisherConfigTransfer);
    }

    /**
     * @return void
     */
    public function testProductCreatedEventSuccessfullyPublishedToMessageBroker(): void
    {
        // Arrange
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);
        $concreteProduct = $this->tester->haveFullProduct();

        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setProductIds([$concreteProduct->getIdProductConcrete()])
            ->setEventName(ProductCreatedTransfer::class);

        // Assert
        $this->tester->assertProductSuccessfullyPublishedViaMessageBroker(
            $this->messageBrokerFacade,
            $concreteProduct,
            ProductCreatedTransfer::class,
        );

        // Act
        $this->tester->getProductFacade()->emitPublishProductToMessageBroker($productPublisherConfigTransfer);
    }

    /**
     * @return void
     */
    public function testProductUpdatedEventSuccessfullyPublishedToMessageBroker(): void
    {
        // Arrange
        $this->tester->setDependency(StoreDependencyProvider::PLUGINS_STORE_COLLECTION_EXPANDER, []);
        $concreteProduct = $this->tester->haveFullProduct();

        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setProductIds([$concreteProduct->getIdProductConcrete()])
            ->setEventName(ProductUpdatedTransfer::class);

        // Assert
        $this->tester->assertProductSuccessfullyPublishedViaMessageBroker(
            $this->messageBrokerFacade,
            $concreteProduct,
            ProductUpdatedTransfer::class,
        );

        // Act
        $this->tester->getProductFacade()->emitPublishProductToMessageBroker($productPublisherConfigTransfer);
    }

    /**
     * @return void
     */
    public function testProductMessageBrokerPublisherThrowsExceptionOnPublishWhenEventNameIsWrong()
    {
        // Arrange
        $concreteProduct = $this->tester->haveFullProduct();
        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setProductIds([$concreteProduct->getIdProductConcrete()])
            ->setEventName('unknown');

        // Assert
        $this->tester->expectThrowable(
            ProductPublisherEventNameMismatchException::class,
            function () use ($productPublisherConfigTransfer) {
                // Act
                $this->tester->getProductFacade()->emitPublishProductToMessageBroker($productPublisherConfigTransfer);
            },
        );
    }

    /**
     * @return void
     */
    public function testProductDeletedEventSuccessfullyPublishedToMessageBroker(): void
    {
        // Arrange
        $concreteProduct = $this->tester->haveFullProduct();

        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setProductIds([$concreteProduct->getIdProductConcrete()])
            ->setEventName(ProductDeletedTransfer::class);

        // Assert
        $this->tester->assertProductSuccessfullyUnpublishedViaMessageBroker(
            $this->messageBrokerFacade,
            $concreteProduct->getSku(),
            ProductDeletedTransfer::class,
        );

        // Act
        $this->tester->getProductFacade()->emitUnpublishProductToMessageBroker($productPublisherConfigTransfer);
    }

    /**
     * @return void
     */
    public function testProductMessageBrokerPublisherThrowsExceptionOnUnpublishWhenEventNameIsWrong(): void
    {
        // Arrange
        $concreteProduct = $this->tester->haveFullProduct();

        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setProductIds([$concreteProduct->getIdProductConcrete()])
            ->setEventName('unknown');

        // Assert
        $this->tester->expectThrowable(
            ProductPublisherEventNameMismatchException::class,
            function () use ($productPublisherConfigTransfer) {
                // Act
                $this->tester->getProductFacade()->emitUnpublishProductToMessageBroker($productPublisherConfigTransfer);
            },
        );
    }

    /**
     * @return void
     */
    public function testProductDeletedEventNotPublishedToMessageBrokerIfProductsListIsEmpty(): void
    {
        // Arrange
        $productPublisherConfigTransfer = (new ProductPublisherConfigTransfer())
            ->setEventName(ProductDeletedTransfer::class);

        // Assert
        $this->messageBrokerFacade
            ->expects($this->never())
            ->method('sendMessage');

        // Act
        $this->tester->getProductFacade()->emitUnpublishProductToMessageBroker($productPublisherConfigTransfer);
    }

    /**
     * @return void
     */
    public function testProductExportEventsSuccessfullyTriggered(): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->tester->haveFullProduct();
        $productConcreteTransfer2 = $this->tester->haveFullProduct();

        /** @var \Generated\Shared\Transfer\StoreTransfer $storeTransfer */
        $storeTransfer = $this->tester->getStoreFacade()->getCurrentStore();

        $this->tester->deleteProductFromStore($productConcreteTransfer2, $storeTransfer);

        $productExportCriteriaTransfer = (new ProductExportCriteriaTransfer())
            ->setStoreReference($storeTransfer->getStoreReference());

        // Assert
        $this->eventFacade
            ->expects($this->atLeastOnce())
            ->method('triggerBulk')
            ->with(ProductEvents::PRODUCT_CONCRETE_EXPORT, $this->callback(
                function ($transfers) use ($productConcreteTransfer2) {
                    $this->assertNotEmpty($transfers);
                    $this->assertInstanceOf(EventEntityTransfer::class, $transfers[0]);
                    $this->assertNotContains($productConcreteTransfer2, $transfers);

                    return true;
                },
            ));

        // Act
        $this->tester->getProductFacade()->triggerProductExportEvents($productExportCriteriaTransfer);
    }

    /**
     * @dataProvider triggerProductExportEventsTriggerFailsDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductExportCriteriaTransfer $productExportCriteriaTransfer
     *
     * @return void
     */
    public function testTriggerProductExportEventsTriggerFails(
        ProductExportCriteriaTransfer $productExportCriteriaTransfer
    ): void {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();

        // Assert
        $this->eventFacade->expects($this->never())
            ->method('triggerBulk');

        // Act
        $this->tester->getProductFacade()->triggerProductExportEvents($productExportCriteriaTransfer);
    }

    /**
     * @return array<string, array<string, \Generated\Shared\Transfer\ProductExportCriteriaTransfer>>
     */
    public function triggerProductExportEventsTriggerFailsDataProvider(): array
    {
        return [
            'store reference is missing' => [
                'export criteria' => new ProductExportCriteriaTransfer(),
            ],
            'store with given reference doesn\'t exist' => [
                'export criteria' => (new ProductExportCriteriaTransfer())
                    ->setStoreReference(static::UNEXISTING_STORE_REFERENCE),
            ],
        ];
    }

    /**
     * @param string $localizedAttributeName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer(string $localizedAttributeName, LocaleTransfer $localeTransfer): LocalizedAttributesTransfer
    {
        return (new LocalizedAttributesTransfer())
            ->setName($localizedAttributeName)
            ->setLocale($localeTransfer);
    }

    /**
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function getLocales(): array
    {
        return [
            static::LOCALE_NAME_DE => $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_DE]),
            static::LOCALE_NAME_EN => $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_EN]),
        ];
    }

    /**
     * @return void
     */
    public function testGetProductConcreteTransfersByProductIdsReturnsAttributesArray(): void
    {
        // Arrange
        $productConcreteIds = $this->tester->getProductConcreteIds();
        $expectedIdCount = count($productConcreteIds);

        // Act
        $productConcreteTransfers = $this->tester->getProductFacade()->getProductConcreteTransfersByProductIds($productConcreteIds);

        // Assert
        $this->assertTrue($expectedIdCount > 0);
        $this->assertSame($expectedIdCount, count($productConcreteTransfers));
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertIsArray($productConcreteTransfer->getAttributes());
        }
    }
}
