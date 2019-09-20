<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductAbstractSetTableMap;
use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetDataTableMap;
use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearchQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacade;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductAbstractProductSetPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetDataPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageProductImageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageProductImageSetImageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageProductImageSetSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageSearchListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageSearchUnpublishListener;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageUrlSearchListener;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToSearchBridge;
use Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainer;
use Spryker\Zed\Url\Dependency\UrlEvents;
use SprykerTest\Zed\ProductSetPageSearch\Business\ProductSetPageSearchBusinessFactoryMock;
use SprykerTest\Zed\ProductSetPageSearch\ProductSetPageSearchConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSetPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductSetPageSearchListenerTest
 * Add your own group annotations below this line
 */
class ProductSetPageSearchListenerTest extends Unit
{
    protected const MESSAGE_PRODUCT_SET_NOT_DELETED = 'Product set has not been removed.';
    protected const MESSAGE_UNNECESSARY_PRODUCT_SET_DELETED = 'Unnecessary product set was has been removed.';

    /**
     * @var \SprykerTest\Zed\ProductSetPageSearch\ProductSetPageSearchCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface
     */
    protected $productSetFacadeInterface;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        if (!$this->tester->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }

        $this->productSetFacadeInterface = $this->tester->getLocator()->productSet()->facade();
    }

    /**
     * @return void
     */
    public function testProductSetPageSearchListenerStoreData()
    {
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet(1)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productSetPageSearchListener = new ProductSetPageSearchListener();
        $productSetPageSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $productSetPageSearchListener->handleBulk($eventTransfers, ProductSetEvents::PRODUCT_SET_PUBLISH);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return void
     */
    public function testProductSetDataPageSearchListenerStoreData()
    {
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet(1)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productSetDataPageSearchListener = new ProductSetDataPageSearchListener();
        $productSetDataPageSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductSetDataTableMap::COL_FK_PRODUCT_SET => 1,
            ]),
        ];
        $productSetDataPageSearchListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_SET_DATA_CREATE);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return void
     */
    public function testProductSetSearchUnpublishListener(): void
    {
        // Arrange
        $productSetTransfers = [
            $this->tester->generateProductSetTransfer(),
            $this->tester->generateProductSetTransfer(),
            $this->tester->generateProductSetTransfer(),
        ];
        $this->tester->publishProductSetTransfers($productSetTransfers, $this->getProductSetPageSearchFacade());
        $productSetBeforeUnpublish = SpyProductSetPageSearchQuery::create()->count();
        $productSetDeletedId = $productSetTransfers[0]->getIdProductSet();
        $this->productSetFacadeInterface->deleteProductSet($productSetTransfers[0]);

        $productSetSearchUnpublishListener = new ProductSetPageSearchUnpublishListener();
        $productSetSearchUnpublishListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($productSetDeletedId),
        ];

        // Act
        $productSetSearchUnpublishListener->handleBulk($eventTransfers, ProductSetEvents::PRODUCT_SET_UNPUBLISH);

        // Assert
        $this->assertSame(
            0,
            SpyProductSetPageSearchQuery::create()->filterByFkProductSet($productSetDeletedId)->count(),
            static::MESSAGE_PRODUCT_SET_NOT_DELETED
        );
        $this->assertGreaterThan(
            SpyProductSetPageSearchQuery::create()->count(),
            $productSetBeforeUnpublish,
            static::MESSAGE_UNNECESSARY_PRODUCT_SET_DELETED
        );
    }

    /**
     * @return void
     */
    public function testProductAbstractProductSetPageSearchListenerStoreData()
    {
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet(1)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productAbstractProductSetPageSearchListener = new ProductAbstractProductSetPageSearchListener();
        $productAbstractProductSetPageSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractSetTableMap::COL_FK_PRODUCT_SET => 1,
            ]),
        ];
        $productAbstractProductSetPageSearchListener->handleBulk($eventTransfers, ProductSetEvents::ENTITY_SPY_PRODUCT_ABSTRACT_SET_CREATE);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return void
     */
    public function testProductSetPageProductImageSetSearchListenerStoreData()
    {
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet(1)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productSetPageProductImageSetSearchListener = new ProductSetPageProductImageSetSearchListener();
        $productSetPageProductImageSetSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductImageSetTableMap::COL_FK_RESOURCE_PRODUCT_SET => 1,
            ]),
        ];
        $productSetPageProductImageSetSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_CREATE);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return void
     */
    public function testProductSetPageUrlSearchListenerStoreData()
    {
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet(1)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productSetPageUrlSearchListener = new ProductSetPageUrlSearchListener();
        $productSetPageUrlSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET => 1,
            ])->setModifiedColumns([
                SpyUrlTableMap::COL_URL,
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET,
            ]),
        ];
        $productSetPageUrlSearchListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_UPDATE);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return void
     */
    public function testProductSetPageProductImageSearchListenerStoreData()
    {
        $this->markTestSkipped(
            'These tests need to be re-written in CC-940'
        );
        $productSetPageQueryContainer = new ProductSetPageSearchQueryContainer();
        $productSetIds = $productSetPageQueryContainer->queryProductSetIdsByProductImageIds([209])->find()->getData();
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet_In($productSetIds)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productSetPageProductImageSearchListener = new ProductSetPageProductImageSearchListener();
        $productSetPageProductImageSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(209),
        ];
        $productSetPageProductImageSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return void
     */
    public function testProductSetPageProductImageSetImageSearchListenerStoreData()
    {
        $this->markTestSkipped(
            'These tests need to be re-written in CC-940'
        );
        $productSetPageQueryContainer = new ProductSetPageSearchQueryContainer();
        $productSetIds = $productSetPageQueryContainer->queryProductSetIdsByProductImageSetToProductImageIds([1021])->find()->getData();
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet_In($productSetIds)->delete();
        $beforeCount = SpyProductSetPageSearchQuery::create()->count();

        // Act
        $productSetPageProductImageSetImageSearchListener = new ProductSetPageProductImageSetImageSearchListener();
        $productSetPageProductImageSetImageSearchListener->setFacade($this->getProductSetPageSearchFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1021),
        ];
        $productSetPageProductImageSetImageSearchListener->handleBulk($eventTransfers, ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_UPDATE);

        // Assert
        $afterCount = SpyProductSetPageSearchQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $afterCount);
        $this->assertProductSetPageSearch();
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacade
     */
    protected function getProductSetPageSearchFacade()
    {
        $searchFacadeMock = $this->getMockBuilder(ProductSetPageSearchToSearchBridge::class)->disableOriginalConstructor()->getMock();
        $searchFacadeMock->method('transformPageMapToDocumentByMapperName')->willReturn([]);
        $factory = new ProductSetPageSearchBusinessFactoryMock($searchFacadeMock);
        $factory->setConfig(new ProductSetPageSearchConfigMock());

        $facade = new ProductSetPageSearchFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertProductSetPageSearch()
    {
        $productSet = SpyProductSetPageSearchQuery::create()->orderByFkProductSet()->findOneByFkProductSet(1);
        $this->assertNotNull($productSet);
        $data = $productSet->getStructuredData();
        $encodedData = json_decode($data, true);
        $this->assertSame('HP Product Set', $encodedData['name']);
    }
}
