<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use ReflectionClass;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategoryNodeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceProductStoreSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceTypeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractPublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractUnpublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageUrlSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander\ProductCategoryPageDataExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainer;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactoryMock;
use SprykerTest\Zed\ProductPageSearch\ProductPageSearchConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductPageSearchListenerTest
 * Add your own group annotations below this line
 */
class ProductPageSearchListenerTest extends Unit
{
    protected const NUMBER_OF_LOCALES = 1;
    protected const NUMBER_OF_STORES = 3;

    /**
     * @var \SprykerTest\Zed\ProductPageSearch\ProductPageSearchCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcreteTransfer;

    /**
     * @var \Generated\Shared\Transfer\CategoryTransfer
     */
    protected $categoryTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    protected $productImageSetTransfer;

    /**
     * @var \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productConcreteTransfer = $this->tester->haveProduct();
        $this->productAbstractTransfer = $this->tester->getProductFacade()->findProductAbstractById(
            $this->productConcreteTransfer->getFkProductAbstract()
        );

        $localizedAttributes = $this->tester->generateLocalizedAttributes();

        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);
        $this->addStoreRelationToProductAbstracts($this->productAbstractTransfer);
        $this->tester->addLocalizedAttributesToProductConcrete($this->productConcreteTransfer, $localizedAttributes);

        $locale = $this->getLocaleFacade()->getCurrentLocale();
        $this->categoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $locale]);
        $this->getProductSearchFacade()->activateProductSearch($this->productConcreteTransfer->getIdProductConcrete(), [$locale]);

        $productIdsToAssign = [$this->productAbstractTransfer->getIdProductAbstract()];

        $this->addProductToCategoryMappings($this->categoryTransfer->getIdCategory(), $productIdsToAssign);

        $this->productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::ID_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
        ]);

        $priceProductOverride = [
            PriceProductTransfer::ID_PRICE_PRODUCT => $this->productAbstractTransfer->getIdProductAbstract(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getSku(),
        ];

        $this->priceProductTransfer = $this->tester->havePriceProduct($priceProductOverride);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after(); // TODO: Change the autogenerated stub

        $this->cleanStaticProperty();
    }

    /**
     * @return void
     */
    public function testProductPageProductAbstractListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductAbstractListener = new ProductPageProductAbstractListener();
        $productPageProductAbstractListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertSame($beforeCount + static::NUMBER_OF_STORES * static::NUMBER_OF_LOCALES, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductAbstractPublishListener(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductAbstractPublishListener = new ProductPageProductAbstractPublishListener();
        $productPageProductAbstractPublishListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $productPageProductAbstractPublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertSame($beforeCount + static::NUMBER_OF_STORES * static::NUMBER_OF_LOCALES, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductAbstractUnpublishListener(): void
    {
        // Prepare
        $idProductAbstract = $this->productAbstractTransfer->getIdProductAbstract();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($idProductAbstract)->delete();

        // Act
        $productPageProductAbstractListener = new ProductPageProductAbstractPublishListener();
        $productPageProductAbstractListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idProductAbstract),
        ];
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        $productPageProductAbstractListener = new ProductPageProductAbstractUnpublishListener();
        $productPageProductAbstractListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idProductAbstract),
        ];
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertSame($beforeCount - static::NUMBER_OF_STORES * static::NUMBER_OF_LOCALES, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageCategoryNodeSearchListenerStoreData(): void
    {
        // Prepare
        $categoryIds = [$this->categoryTransfer->getIdCategory()];

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByCategoryIds($categoryIds)->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageCategoryNodeSearchListener = new ProductPageCategoryNodeSearchListener();
        $productPageCategoryNodeSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryNodeTableMap::COL_FK_CATEGORY => $this->categoryTransfer->getIdCategory(),
            ]),
        ];
        $productPageCategoryNodeSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageCategorySearchListenerStoreData(): void
    {
        // Prepare
        $categoryIds = [$this->categoryTransfer->getIdCategory()];

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByCategoryIds($categoryIds)->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageCategorySearchListener = new ProductPageCategorySearchListener();
        $productPageCategorySearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())
                ->setId($this->categoryTransfer->getIdCategory())
                ->setModifiedColumns([SpyCategoryTableMap::COL_CATEGORY_KEY]),
        ];
        $productPageCategorySearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageImageSetProductImageSearchListenerStoreData(): void
    {
        // Prepare
        $productImageSetToProductImageEntity = SpyProductImageSetToProductImageQuery::create()->findOneByFkProductImageSet(
            $this->productImageSetTransfer->getIdProductImageSet()
        );

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductImageSetToProductImageIds([$productImageSetToProductImageEntity->getIdProductImageSetToProductImage()])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageImageSetProductImageSearchListener = new ProductPageImageSetProductImageSearchListener();
        $productPageImageSetProductImageSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [];

        if ($productImageSetToProductImageEntity) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageSetToProductImageEntity->getIdProductImageSetToProductImage());
        }

        $productPageImageSetProductImageSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageImageSetSearchListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageImageSetSearchListener = new ProductPageImageSetSearchListener();
        $productPageImageSetSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productPageImageSetSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageLocalizedAttributesSearchListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageLocalizedAttributesSearchListener = new ProductPageLocalizedAttributesSearchListener();
        $productPageLocalizedAttributesSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productPageLocalizedAttributesSearchListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPagePriceProductStoreSearchListenerStoreData(): void
    {
        // Prepare
        $priceProductIds = [
            $this->priceProductTransfer->getIdPriceProduct(),
        ];

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryAllProductAbstractIdsByPriceProductIds($priceProductIds)->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPagePriceProductStoreSearchListener = new ProductPagePriceProductStoreSearchListener();
        $productPagePriceProductStoreSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => $this->priceProductTransfer->getIdPriceProduct(),
            ]),
        ];
        $productPagePriceProductStoreSearchListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPagePriceSearchListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPagePriceSearchListener = new ProductPagePriceSearchListener();
        $productPagePriceSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productPagePriceSearchListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPagePriceTypeSearchListenerStoreData(): void
    {
        // Prepare
        $priceTypeIds = [
            $this->priceProductTransfer->getFkPriceType(),
        ];

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryAllProductAbstractIdsByPriceTypeIds($priceTypeIds)->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPagePriceTypeSearchListener = new ProductPagePriceTypeSearchListener();
        $productPagePriceTypeSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->priceProductTransfer->getFkPriceType()),
        ];
        $productPagePriceTypeSearchListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageProductCategorySearchListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductCategorySearchListener = new ProductPageProductCategorySearchListener();
        $productPageProductCategorySearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productPageProductCategorySearchListener->handleBulk($eventTransfers, ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductConcreteLocalizedAttributesSearchListenerStoreData(): void
    {
        // Prepare
        $productIds = [
            $this->productConcreteTransfer->getIdProductConcrete(),
        ];

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductIds($productIds)->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductConcreteLocalizedAttributesSearchListener = new ProductPageProductConcreteLocalizedAttributesSearchListener();
        $productPageProductConcreteLocalizedAttributesSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $this->productConcreteTransfer->getIdProductConcrete(),
            ]),
        ];
        $productPageProductConcreteLocalizedAttributesSearchListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageProductConcreteSearchListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductConcreteSearchListener = new ProductPageProductConcreteSearchListener();
        $productPageProductConcreteSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productPageProductConcreteSearchListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductImageSearchListenerStoreData(): void
    {
        // Prepare
        $productImageIds = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $productImageIds[] = $productImageTransfer->getIdProductImage();
        }

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductImageIds($productImageIds)->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductImageSearchListener = new ProductPageProductImageSearchListener();
        $productPageProductImageSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [];

        foreach ($this->productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productImageTransfer->getIdProductImage());
        }

        $productPageProductImageSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageUrlSearchListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageUrlSearchListener = new ProductPageUrlSearchListener();
        $productPageUrlSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ])
                ->setModifiedColumns([
                    SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT,
                ]),
        ];
        $productPageUrlSearchListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacade
     */
    protected function getProductPageSearchFacade()
    {
        $productPageSearchToSearchBridgeMock = $this->getMockBuilder(ProductPageSearchToSearchBridge::class)->disableOriginalConstructor()->getMock();
        $productPageSearchToSearchBridgeMock->method('transformPageMapToDocumentByMapperName')->willReturn([]);
        $factory = new ProductPageSearchBusinessFactoryMock($productPageSearchToSearchBridgeMock);
        $factory->setConfig(new ProductPageSearchConfigMock());

        $facade = new ProductPageSearchFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertProductPageAbstractSearch(): void
    {
        $productPageSearchEntity = SpyProductAbstractPageSearchQuery::create()
            ->orderByIdProductAbstractPageSearch()
            ->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $urlCollectionEntity = SpyUrlQuery::create()
            ->orderByFkResourceProductAbstract()
            ->findByFkResourceProductAbstract($this->productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($productPageSearchEntity);
        $this->assertNotNull($urlCollectionEntity->count());

        $data = $productPageSearchEntity->getStructuredData();
        $encodedData = json_decode($data, true);
        $this->assertContains($encodedData['url'], $urlCollectionEntity->getColumnValues('url'));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function addStoreRelationToProductAbstracts(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $idStores = $this->getIdStores();

        $productAbstractTransfer->setStoreRelation((new StoreRelationTransfer())->setIdStores($idStores));

        $this->tester->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @return void
     */
    protected function addProductToCategoryMappings(int $idCategory, array $productIdsToAssign): void
    {
        $this->getProductFacade()->createProductCategoryMappings($idCategory, $productIdsToAssign);
    }

    /**
     * @return array
     */
    protected function getIdStores(): array
    {
        $storeIds = [];

        foreach ($this->getStoreFacade()->getAllStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected function getProductFacade(): ProductCategoryFacadeInterface
    {
        return $this->tester->getLocator()->productCategory()->facade();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->tester->getLocator()->store()->facade();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->tester->getLocator()->locale()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface
     */
    protected function getProductSearchFacade(): ProductSearchFacadeInterface
    {
        return $this->tester->getLocator()->productSearch()->facade();
    }

    /**
     * @return void
     */
    protected function cleanStaticProperty(): void
    {
        $reflectedClass = new ReflectionClass(ProductCategoryPageDataExpanderPlugin::class);
        $property = $reflectedClass->getProperty('categoryTree');
        $property->setAccessible(true);
        $property->setValue(null);
    }
}
