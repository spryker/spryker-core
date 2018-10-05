<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\Product\Dependency\ProductEvents;
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
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductCategorySearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteLocalizedAttributesSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductConcreteSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageProductImageSearchListener;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\ProductPageUrlSearchListener;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainer;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactoryMock;
use SprykerTest\Zed\ProductPageSearch\ProductPageSearchConfigMock;

/**
 * Auto-generated group annotations
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
    public const NUMBER_OF_LOCALES = 2;
    public const NUMBER_OF_STORES = 3;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testProductPageProductAbstractListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductAbstractListener = new ProductPageProductAbstractListener();
        $productPageProductAbstractListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
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
    public function testProductPageCategoryNodeSearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByCategoryIds([7])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageCategoryNodeSearchListener = new ProductPageCategoryNodeSearchListener();
        $productPageCategoryNodeSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyCategoryNodeTableMap::COL_FK_CATEGORY => 7,
            ]),
        ];
        $productPageCategoryNodeSearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 40, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageCategorySearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByCategoryIds([7])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageCategorySearchListener = new ProductPageCategorySearchListener();
        $productPageCategorySearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())
                ->setId(7)
                ->setModifiedColumns([SpyCategoryTableMap::COL_CATEGORY_KEY]),
        ];
        $productPageCategorySearchListener->handleBulk($eventTransfers, CategoryEvents::ENTITY_SPY_CATEGORY_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 40, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageImageSetProductImageSearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductImageSetToProductImageIds([1])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageImageSetProductImageSearchListener = new ProductPageImageSetProductImageSearchListener();
        $productPageImageSetProductImageSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productPageImageSetProductImageSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageImageSetSearchListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageImageSetSearchListener = new ProductPageImageSetSearchListener();
        $productPageImageSetSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productPageImageSetSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageLocalizedAttributesSearchListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageLocalizedAttributesSearchListener = new ProductPageLocalizedAttributesSearchListener();
        $productPageLocalizedAttributesSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productPageLocalizedAttributesSearchListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPagePriceProductStoreSearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryAllProductAbstractIdsByPriceProductIds([1])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPagePriceProductStoreSearchListener = new ProductPagePriceProductStoreSearchListener();
        $productPagePriceProductStoreSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT => 1,
            ]),
        ];
        $productPagePriceProductStoreSearchListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPagePriceSearchListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPagePriceSearchListener = new ProductPagePriceSearchListener();
        $productPagePriceSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productPagePriceSearchListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPagePriceTypeSearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryAllProductAbstractIdsByPriceTypeIds([1])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPagePriceTypeSearchListener = new ProductPagePriceTypeSearchListener();
        $productPagePriceTypeSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productPagePriceTypeSearchListener->handleBulk($eventTransfers, PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 428, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageProductCategorySearchListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductCategorySearchListener = new ProductPageProductCategorySearchListener();
        $productPageProductCategorySearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productPageProductCategorySearchListener->handleBulk($eventTransfers, ProductCategoryEvents::ENTITY_SPY_PRODUCT_CATEGORY_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductConcreteLocalizedAttributesSearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductIds([52])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductConcreteLocalizedAttributesSearchListener = new ProductPageProductConcreteLocalizedAttributesSearchListener();
        $productPageProductConcreteLocalizedAttributesSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT => 52,
            ]),
        ];
        $productPageProductConcreteLocalizedAttributesSearchListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageProductConcreteSearchListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductConcreteSearchListener = new ProductPageProductConcreteSearchListener();
        $productPageProductConcreteSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT => 1,
            ]),
        ];
        $productPageProductConcreteSearchListener->handleBulk($eventTransfers, ProductEvents::ENTITY_SPY_PRODUCT_CREATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
        $this->assertProductPageAbstractSearch();
    }

    /**
     * @return void
     */
    public function testProductPageProductImageSearchListenerStoreData()
    {
        $productPageSearchQueryContainer = new ProductPageSearchQueryContainer();
        $productAbstractIds = $productPageSearchQueryContainer->queryProductAbstractIdsByProductImageIds([1])->find()->getData();
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageProductImageSearchListener = new ProductPageProductImageSearchListener();
        $productPageProductImageSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productPageProductImageSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
    }

    /**
     * @return void
     */
    public function testProductPageUrlSearchListenerStoreData()
    {
        SpyProductAbstractPageSearchQuery::create()->filterByFkProductAbstract(1)->delete();
        $beforeCount = SpyProductAbstractPageSearchQuery::create()->count();

        // Act
        $productPageUrlSearchListener = new ProductPageUrlSearchListener();
        $productPageUrlSearchListener->setFacade($this->getProductPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT => 1,
            ])
            ->setModifiedColumns([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT,
            ]),
        ];
        $productPageUrlSearchListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $afterCount = SpyProductAbstractPageSearchQuery::create()->count();
        $this->assertGreaterThanOrEqual($beforeCount + 2, $afterCount);
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
    protected function assertProductPageAbstractSearch()
    {
        $productPageSearchEntity = SpyProductAbstractPageSearchQuery::create()
            ->orderByIdProductAbstractPageSearch()
            ->findOneByFkProductAbstract(1);
        $this->assertNotNull($productPageSearchEntity);
        $data = $productPageSearchEntity->getStructuredData();
        $encodedData = json_decode($data, true);
        $this->assertSame('/de/canon-ixus-160-001', $encodedData['url']);
    }
}
