<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use ReflectionClass;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\PriceProductDefaultProductPagePublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductAbstractStoreProductConcretePageSearchPublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductAbstractListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageProductConcreteListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageSetListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductImageSetToProductImageListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductConcretePageSearchProductLocalizedAttributesListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageAvailabilityStockSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategoryNodeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageImageSetSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPagePriceTypeSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractPublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractStoreSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductAbstractUnpublishListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageUrlSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander\ProductCategoryPageDataExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainer;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepository;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

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
    use LocatorHelperTrait;
    use ConfigHelperTrait;

    /**
     * @var int
     */
    protected const NUMBER_OF_LOCALES = 1;

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
    protected function setUp(): void
    {
        parent::setUp();

        $this->productConcreteTransfer = $this->tester->haveFullProduct();
        $this->productAbstractTransfer = $this->tester->getProductFacade()->findProductAbstractById(
            $this->productConcreteTransfer->getFkProductAbstract(),
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
        $this->getConfigHelper()->mockConfigMethod('isSendingToQueue', false);
        $this->mockSearchFacade();
        $this->tester->cleanUpProcessedAbstractProductIds();
        $this->tester->cleanUpProcessedConcreteProductIds();
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
    public function testProductPageProductAbstractListenerStoreDataToProcessUniqueProducts(): void
    {
        // Assert
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $productPageSearchFacadeMock = $this->tester->mockProductPageSearchFacade();
        $productPageSearchFacadeMock->expects($this->once())
            ->method('publish')
            ->with([$this->productAbstractTransfer->getIdProductAbstract(), 1234]);
        $productPageSearchFacadeMock->expects($this->once())
            ->method('unpublish')
            ->with([$this->productAbstractTransfer->getIdProductAbstract(), 1234]);
        $productPageProductAbstractPublishListener = new ProductPageProductAbstractPublishListener();
        $productPageProductAbstractPublishListener->setFacade($productPageSearchFacadeMock);
        $productPageCategoryNodeSearchListener = new ProductPageCategoryNodeSearchListener();
        $productPageCategoryNodeSearchListener->setFacade($productPageSearchFacadeMock);
        $productPageProductAbstractUnpublishListener = new ProductPageProductAbstractUnpublishListener();
        $productPageProductAbstractUnpublishListener->setFacade($productPageSearchFacadeMock);
        $productPageProductAbstractListener = new ProductPageProductAbstractListener();
        $productPageProductAbstractListener->setFacade($productPageSearchFacadeMock);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
            (new EventEntityTransfer())->setId(1234),
        ];

        // Act
        $productPageProductAbstractPublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);
        $productPageCategoryNodeSearchListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);
        $productPageProductAbstractUnpublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);
    }

    /**
     * @return void
     */
    public function testProductPageProductConcreteListenerStoreDataToProcessUniqueProducts(): void
    {
        // Assert
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productConcreteTransfer->getIdProductConcrete())->delete();
        $productPageSearchFacadeMock = $this->tester->mockProductPageSearchFacade();
        $productPageSearchFacadeMock->expects($this->once())
            ->method('publishProductConcretes')
            ->with([$this->productConcreteTransfer->getIdProductConcrete(), 1234]);
        $productPageSearchFacadeMock->expects($this->once())
            ->method('unpublishProductConcretes')
            ->with([$this->productConcreteTransfer->getIdProductConcrete(), 1234]);
        $productConcretePageSearchProductListener = new ProductConcretePageSearchProductListener();
        $productConcretePageSearchProductListener->setFacade($productPageSearchFacadeMock);
        $productConcretePageSearchProductImageSetListener = new ProductConcretePageSearchProductImageSetListener();
        $productConcretePageSearchProductImageSetListener->setFacade($productPageSearchFacadeMock);
        $productConcretePageSearchProductAbstractListener = new ProductConcretePageSearchProductAbstractListener();
        $productConcretePageSearchProductAbstractListener->setFacade($productPageSearchFacadeMock);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
            (new EventEntityTransfer())->setId($this->productConcreteTransfer->getIdProductConcrete()),
            (new EventEntityTransfer())->setId(1234),
        ];

        // Act
        $productConcretePageSearchProductListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);
        $productConcretePageSearchProductImageSetListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_PUBLISH);
        $productConcretePageSearchProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_CONCRETE_UNPUBLISH);
    }

    /**
     * @return void
     */
    public function testProductPageProductAbstractListenerStoreData(): void
    {
        // Prepare
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();
        $productAbstractStoreNames = $this->tester->getProductAbstractStoreNamesByIdProductAbstract(
            $this->productAbstractTransfer->getIdProductAbstract(),
        );

        // Act
        $productPageProductAbstractListener = new ProductPageProductAbstractListener();
        $productPageProductAbstractListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();

        $this->assertSame($beforeCount + count($productAbstractStoreNames) * static::NUMBER_OF_LOCALES, $afterCount);
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
        $productAbstractStoreNames = $this->tester->getProductAbstractStoreNamesByIdProductAbstract(
            $this->productAbstractTransfer->getIdProductAbstract(),
        );

        // Act
        $productPageProductAbstractPublishListener = new ProductPageProductAbstractPublishListener();
        $productPageProductAbstractPublishListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $productPageProductAbstractPublishListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertSame($beforeCount + count($productAbstractStoreNames) * static::NUMBER_OF_LOCALES, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductAbstractUnpublishListener(): void
    {
        // Prepare
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->getLocatorHelper()->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
        $idProductAbstract = $this->productAbstractTransfer->getIdProductAbstract();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract($idProductAbstract)->delete();
        $productAbstractStoreNames = $this->tester->getProductAbstractStoreNamesByIdProductAbstract(
            $this->productAbstractTransfer->getIdProductAbstract(),
        );

        // Act
        $productPageProductAbstractListener = new ProductPageProductAbstractPublishListener();
        $productPageProductAbstractListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idProductAbstract),
        ];
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_PUBLISH);
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        $productPageProductAbstractListener = new ProductPageProductAbstractUnpublishListener();
        $productPageProductAbstractListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idProductAbstract),
        ];
        $productPageProductAbstractListener->handleBulk($eventTransfers, ProductEvents::PRODUCT_ABSTRACT_UNPUBLISH);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertSame($beforeCount - count($productAbstractStoreNames) * static::NUMBER_OF_LOCALES, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageCategoryNodeSearchListenerStoreData(): void
    {
        // Prepare
        $categoryIds = [$this->categoryTransfer->getIdCategory()];

        $productPageSearchRepository = new ProductPageSearchRepository();
        $productAbstractIds = $productPageSearchRepository->getProductAbstractIdsByCategoryIds($categoryIds);
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageCategoryNodeSearchListener = new ProductPageCategoryNodeSearchListener();
        $productPageCategoryNodeSearchListener->setFacade($this->tester->getFacade());

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

        $productPageSearchRepository = new ProductPageSearchRepository();
        $productAbstractIds = $productPageSearchRepository->getProductAbstractIdsByCategoryIds($categoryIds);
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageCategorySearchListener = new ProductPageCategorySearchListener();
        $productPageCategorySearchListener->setFacade($this->tester->getFacade());

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
            $this->productImageSetTransfer->getIdProductImageSet(),
        );

        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductImageSetToProductImageIds([$productImageSetToProductImageEntity->getIdProductImageSetToProductImage()])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageImageSetProductImageSearchListener = new ProductPageImageSetProductImageSearchListener();
        $productPageImageSetProductImageSearchListener->setFacade($this->tester->getFacade());

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
        $productPageImageSetSearchListener->setFacade($this->tester->getFacade());

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
        $productPageLocalizedAttributesSearchListener->setFacade($this->tester->getFacade());

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
    public function testProductPagePriceProductDefaultSearchListenerStoreData(): void
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
        $priceProductDefaultProductPagePublishListener = new PriceProductDefaultProductPagePublishListener();
        $priceProductDefaultProductPagePublishListener->setFacade($this->tester->getFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => $this->priceProductTransfer->getIdPriceProduct(),
            ]),
        ];
        $priceProductDefaultProductPagePublishListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

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
        $productPagePriceSearchListener->setFacade($this->tester->getFacade());

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
        $productPagePriceTypeSearchListener->setFacade($this->tester->getFacade());

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
        $productPageProductCategorySearchListener->setFacade($this->tester->getFacade());

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
        $productPageProductConcreteLocalizedAttributesSearchListener->setFacade($this->tester->getFacade());

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
        $productPageProductConcreteSearchListener->setFacade($this->tester->getFacade());

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
        $productPageProductImageSearchListener->setFacade($this->tester->getFacade());

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
        $productPageUrlSearchListener->setFacade($this->tester->getFacade());

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
     * @dataProvider getProductListenerDataProvider
     *
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface $listener
     * @param callable $callableEventEntityTransfer
     * @param string $eventName
     * @param int $deltaTime
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $invokedCountMatcher
     * @param bool $isAbstractProduct
     *
     * @return void
     */
    public function testProductStorageListenerEventsWithTimestamps(
        EventBulkHandlerInterface $listener,
        callable $callableEventEntityTransfer,
        string $eventName,
        int $deltaTime,
        InvokedCountMatcher $invokedCountMatcher,
        bool $isAbstractProduct
    ): void {
        // Arrange
        $lastStorageTimestamp =
            $isAbstractProduct ?
                $this->tester->getProductAbstractPageSearchEntityTimestamp($this->productConcreteTransfer->getFkProductAbstract()) :
                $this->tester->getProductConcreteStorageEntityTimestamp($this->productConcreteTransfer->getIdProductConcrete());
        if ($lastStorageTimestamp === null) {
            $productStoragePublishListener = $isAbstractProduct ? new ProductPageProductAbstractPublishListener() : new ProductConcretePageSearchProductImageProductConcreteListener();
            $productStoragePublishListener->handleBulk([(new EventEntityTransfer())->setId($isAbstractProduct ? $this->productConcreteTransfer->getFkProductAbstract() : $this->productConcreteTransfer->getIdProductConcrete())], $eventName);
            $this->tester->cleanUpProcessedConcreteProductIds();
            $this->tester->cleanUpProcessedAbstractProductIds();
        }

        // Assert
        $productStorageFacadeMock = $this->tester->mockProductPageSearchFacade();
        $productStorageFacadeMock->expects($invokedCountMatcher)->method($isAbstractProduct ? 'publish' : 'publishProductConcretes');

        // Arrange
        $lastStorageTimestamp =
            $isAbstractProduct ?
                $this->tester->getProductAbstractPageSearchEntityTimestamp($this->productConcreteTransfer->getFkProductAbstract()) :
                $this->tester->getProductConcreteStorageEntityTimestamp($this->productConcreteTransfer->getIdProductConcrete());
        $listener->setFacade($productStorageFacadeMock);
        $eventEntityTransfer = $callableEventEntityTransfer($this->productConcreteTransfer, $this->productImageSetTransfer, $this->priceProductTransfer);
        $eventEntityTransfer->setTimestamp($lastStorageTimestamp + $deltaTime);

        // Act
        $listener->handleBulk([$eventEntityTransfer], $eventName);
        $this->tester->cleanUpProcessedConcreteProductIds();
        $this->tester->cleanUpProcessedAbstractProductIds();
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
            if ($storeTransfer->getDefaultCurrencyIsoCode() === null) {
                continue;
            }
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

    /**
     * @return void
     */
    protected function mockSearchFacade(): void
    {
        $this->tester->setDependency(ProductPageSearchDependencyProvider::FACADE_SEARCH, Stub::make(
            ProductPageSearchToSearchBridge::class,
            [
                'transformPageMapToDocumentByMapperName' => function () {
                    return [];
                },
            ],
        ));
    }

    /**
     * @return array
     */
    public function getProductListenerDataProvider(): array
    {
        return [
             // concrete product - new events with a later timestamp
            'ProductAbstractStoreProductConcretePageSearchPublishListener with a later timestamp' => [
                new ProductAbstractStoreProductConcretePageSearchPublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductAbstractListener with a later timestamp' => [
                new ProductConcretePageSearchProductAbstractListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductImageListener with a later timestamp' => [
                new ProductConcretePageSearchProductImageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productImageSetTransfer->getProductImages()[0]->getIdProductImage());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductImageProductConcreteListener with a later timestamp' => [
                new ProductConcretePageSearchProductImageProductConcreteListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getIdProductConcrete());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductImageSetListener with a later timestamp' => [
                new ProductConcretePageSearchProductImageSetListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductImageSetTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductImageSetToProductImageListener with a later timestamp' => [
                new ProductConcretePageSearchProductImageSetToProductImageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET => $productImageSetTransfer->getIdProductImageSet()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductListener with a later timestamp' => [
                new ProductConcretePageSearchProductListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getIdProductConcrete());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                1,
                $this->once(),
                false,
            ],
            'ProductConcretePageSearchProductLocalizedAttributesListener with a later timestamp' => [
                new ProductConcretePageSearchProductLocalizedAttributesListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE,
                1,
                $this->once(),
                false,
            ],
            // concrete product - events when earlier or equal timestamp
            'ProductAbstractStoreProductConcretePageSearchPublishListener with earlier or equal timestamp' => [
                new ProductAbstractStoreProductConcretePageSearchPublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductAbstractListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductAbstractListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_UPDATE,
                0,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductImageListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductImageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productImageSetTransfer->getProductImages()[0]->getIdProductImage());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                -1000,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductImageProductConcreteListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductImageProductConcreteListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getIdProductConcrete());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductImageSetListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductImageSetListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductImageSetTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductImageSetToProductImageListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductImageSetToProductImageListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET => $productImageSetTransfer->getIdProductImageSet()]);
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getIdProductConcrete());
                },
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                0,
                $this->never(),
                false,
            ],
            'ProductConcretePageSearchProductLocalizedAttributesListener with earlier or equal timestamp' => [
                new ProductConcretePageSearchProductLocalizedAttributesListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE,
                0,
                $this->never(),
                false,
            ],
            // abstract product - new events with a later timestamp
            'ProductPageAvailabilityStockSearchListener with a later timestamp' => [
                new ProductPageAvailabilityStockSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setAdditionalValues(['spy_availability.sku' => $productConcreteTransfer->getSku()]);
                },
                ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE,
                1,
                $this->once(),
                true,
            ],
            'ProductPageImageSetSearchListener with a later timestamp' => [
                new ProductPageImageSetSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageLocalizedAttributesSearchListener with a later timestamp' => [
                new ProductPageLocalizedAttributesSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPagePriceSearchListener with a later timestamp' => [
                new ProductPagePriceSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPagePriceTypeSearchListener with a later timestamp' => [
                new ProductPagePriceTypeSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer, PriceProductTransfer $priceProductTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($priceProductTransfer->getPriceType()->getIdPriceType());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductAbstractPublishListener with a later timestamp' => [
                new ProductPageProductAbstractPublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductAbstractPublishListener with a later timestamp' => [
                new ProductPageProductAbstractPublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductCategorySearchListener with a later timestamp' => [
                new ProductPageProductCategorySearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductConcreteLocalizedAttributesSearchListener with a later timestamp' => [
                new ProductPageProductConcreteLocalizedAttributesSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductConcreteSearchListener with a later timestamp' => [
                new ProductPageProductConcreteSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductImageSearchListener with a later timestamp' => [
                new ProductPageProductImageSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productImageSetTransfer->getProductImages()[0]->getIdProductImage());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductPageProductImageSearchListener with a later timestamp' => [
                new ProductPageProductImageSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()])
                        ->setModifiedColumns([SpyUrlTableMap::COL_URL, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            'ProductSearchListener with a later timestamp' => [
                new ProductSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductSearchTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                1,
                $this->once(),
                true,
            ],
            // abstract product - events when earlier or equal timestamp
            'ProductPageAvailabilityStockSearchListener with earlier or equal timestamp' => [
                new ProductPageAvailabilityStockSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setAdditionalValues(['spy_availability.sku' => $productConcreteTransfer->getSku()]);
                },
                ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE,
                0,
                $this->never(),
                true,
            ],
            'ProductPageImageSetSearchListener with earlier or equal timestamp' => [
                new ProductPageImageSetSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageLocalizedAttributesSearchListener with earlier or equal timestamp' => [
                new ProductPageLocalizedAttributesSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPagePriceSearchListener with earlier or equal timestamp' => [
                new ProductPagePriceSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageProductAbstractPublishListener with earlier or equal timestamp' => [
                new ProductPageProductAbstractPublishListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productConcreteTransfer->getFkProductAbstract());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageProductAbstractStoreSearchListener with earlier or equal timestamp' => [
                new ProductPageProductAbstractStoreSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageProductCategorySearchListener with earlier or equal timestamp' => [
                new ProductPageProductCategorySearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageProductConcreteLocalizedAttributesSearchListener with earlier or equal timestamp' => [
                new ProductPageProductConcreteLocalizedAttributesSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageProductConcreteSearchListener with earlier or equal timestamp' => [
                new ProductPageProductConcreteSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageProductImageSearchListener with earlier or equal timestamp' => [
                new ProductPageProductImageSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer, ProductImageSetTransfer $productImageSetTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId($productImageSetTransfer->getProductImages()[0]->getIdProductImage());
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductPageUrlSearchListener with earlier or equal timestamp' => [
                new ProductPageUrlSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstract()])
                        ->setModifiedColumns([SpyUrlTableMap::COL_URL, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
            'ProductSearchListener with earlier or equal timestamp' => [
                new ProductSearchListener(),
                function (ProductConcreteTransfer $productConcreteTransfer): EventEntityTransfer {
                    return (new EventEntityTransfer())
                        ->setId(1)
                        ->setForeignKeys([SpyProductSearchTableMap::COL_FK_PRODUCT => $productConcreteTransfer->getIdProductConcrete()]);
                },
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                0,
                $this->never(),
                true,
            ],
        ];
    }
}
