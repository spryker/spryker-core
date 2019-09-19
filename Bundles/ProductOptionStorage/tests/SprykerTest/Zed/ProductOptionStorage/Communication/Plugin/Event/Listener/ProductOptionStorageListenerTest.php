<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValuePriceTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\ProductOptionStorage\Persistence\SpyProductAbstractOptionStorageQuery;
use Spryker\Zed\ProductOption\Dependency\ProductOptionEvents;
use Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageBusinessFactory;
use Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacade;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionGroupStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionPublishStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValuePriceStorageListener;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener\ProductOptionValueStorageListener;
use Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainer;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;
use SprykerTest\Zed\ProductOptionStorage\ProductOptionStorageConfigMock;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductOptionStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductOptionStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOptionStorage\ProductOptionStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected $productOptionGroupTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->productOptionGroupTransfer = $this->tester->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD',
                            ProductOptionGroupDataHelper::STORE_NAME => 'DE',
                            MoneyValueTransfer::GROSS_AMOUNT => null,
                            MoneyValueTransfer::NET_AMOUNT => null,
                        ],
                        [
                            ProductOptionGroupDataHelper::CURRENCY_CODE => 'USD',
                            ProductOptionGroupDataHelper::STORE_NAME => null,
                            MoneyValueTransfer::GROSS_AMOUNT => null,
                            MoneyValueTransfer::NET_AMOUNT => null,
                        ],
                    ],
                ],
            ]
        );

        $this->productAbstractTransfer = $this->tester->haveProductAbstract();

        $localizedAttributes = $this->tester->generateLocalizedAttributes();

        $this->tester->addLocalizedAttributesToProductAbstract($this->productAbstractTransfer, $localizedAttributes);

        $this->assignOptionGroupToProductAbstract($this->productOptionGroupTransfer, $this->productAbstractTransfer);
    }

    /**
     * @return void
     */
    public function testProductOptionPublishStorageListenerStoreData()
    {
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionPublishStorageListener = new ProductOptionPublishStorageListener();
        $productOptionPublishStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productAbstractTransfer->getIdProductAbstract()),
        ];
        $productOptionPublishStorageListener->handleBulk($eventTransfers, ProductOptionEvents::PRODUCT_ABSTRACT_PRODUCT_OPTION_PUBLISH);

        // Assert
        $this->assertProductAbstractOptionStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionStorageListenerStoreData()
    {
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract())->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionStorageListener = new ProductOptionStorageListener();
        $productOptionStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT => $this->productAbstractTransfer->getIdProductAbstract(),
            ]),
        ];
        $productOptionStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_ABSTRACT_PRODUCT_OPTION_GROUP_CREATE);

        // Assert
        $this->assertProductAbstractOptionStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionGroupStorageListenerStoreData()
    {
        $productOptionStorageQueryContainer = new ProductOptionStorageQueryContainer();
        $productAbstractIds = $productOptionStorageQueryContainer->queryProductAbstractIdsByProductGroupOptionByIds([$this->productOptionGroupTransfer->getIdProductOptionGroup()])->find()->getData();

        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionGroupStorageListener = new ProductOptionGroupStorageListener();
        $productOptionGroupStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productOptionGroupTransfer->getIdProductOptionGroup()),
        ];
        $productOptionGroupStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_GROUP_UPDATE);

        // Assert
        $this->assertProductAbstractOptionGroupStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionValueStorageListenerStoreData()
    {
        $productOptionStorageQueryContainer = new ProductOptionStorageQueryContainer();
        $productAbstractIds = $productOptionStorageQueryContainer->queryProductAbstractIdsByProductGroupOptionByIds([$this->productOptionGroupTransfer->getIdProductOptionGroup()])->find()->getData();
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionValueStorageListener = new ProductOptionValueStorageListener();
        $productOptionValueStorageListener->setFacade($this->getProductOptionStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyProductOptionValueTableMap::COL_FK_PRODUCT_OPTION_GROUP => $this->productOptionGroupTransfer->getIdProductOptionGroup(),
            ]),
        ];
        $productOptionValueStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_CREATE);

        // Assert
        $this->assertProductAbstractOptionGroupStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testProductOptionValuePriceStorageListenerStoreData()
    {
        $productOptionStorageQueryContainer = new ProductOptionStorageQueryContainer();
        $productAbstractIds = $productOptionStorageQueryContainer->queryProductAbstractIdsByProductGroupOptionByIds([$this->productOptionGroupTransfer->getIdProductOptionGroup()])->find()->getData();
        SpyProductAbstractOptionStorageQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->delete();
        $beforeCount = SpyProductAbstractOptionStorageQuery::create()->count();

        $productOptionValuePriceStorageListener = new ProductOptionValuePriceStorageListener();
        $productOptionValuePriceStorageListener->setFacade($this->getProductOptionStorageFacade());

        $productOptionValues = $this->productOptionGroupTransfer->getProductOptionValues();

        $eventTransfers = [];

        if ($productOptionValues) {
            $productOptionValueTransfer = current($productOptionValues);

            $eventTransfers[] = (new EventEntityTransfer())->setForeignKeys([
                SpyProductOptionValuePriceTableMap::COL_FK_PRODUCT_OPTION_VALUE => $productOptionValueTransfer->getIdProductOptionValue(),
            ]);
        }

        $productOptionValuePriceStorageListener->handleBulk($eventTransfers, ProductOptionEvents::ENTITY_SPY_PRODUCT_OPTION_VALUE_PRICE_CREATE);

        // Assert
        $this->assertProductAbstractOptionGroupStorage($beforeCount);
    }

    /**
     * @return \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacade
     */
    protected function getProductOptionStorageFacade()
    {
        $factory = new ProductOptionStorageBusinessFactory();
        $factory->setConfig(new ProductOptionStorageConfigMock());

        $facade = new ProductOptionStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractOptionGroupStorage($beforeCount)
    {
        $productOptionStorageCount = SpyProductAbstractOptionStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productOptionStorageCount);
    }

    /**
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertProductAbstractOptionStorage($beforeCount)
    {
        $productOptionStorageCount = SpyProductAbstractOptionStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $productOptionStorageCount);
        $spyProductAbstractOptionStorage = SpyProductAbstractOptionStorageQuery::create()->orderByIdProductAbstractOptionStorage()->findOneByFkProductAbstract($this->productAbstractTransfer->getIdProductAbstract());
        $this->assertNotNull($spyProductAbstractOptionStorage);
        $data = $spyProductAbstractOptionStorage->getData();
        $this->assertSame(1, count($data['product_option_groups']));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function assignOptionGroupToProductAbstract(
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): void {
        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractTransfer->getIdProductAbstract()]);

        $this->tester->getProductOptionFacade()->saveProductOptionGroup($productOptionGroupTransfer);
    }
}
