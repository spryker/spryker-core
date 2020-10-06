<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelSearch\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\EventEntityBuilder;
use Generated\Shared\DataBuilder\ProductPayloadBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface;
use Spryker\Zed\ProductLabelSearch\ProductLabelSearchDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelSearch
 * @group Business
 * @group Facade
 * @group ProductLabelSearchFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelSearchFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductLabelSearch\ProductLabelSearchBusinessTester
     */
    protected $tester;

    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT = 'spy_product_label_product_abstract.fk_product_abstract';

    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelStoreTableMap::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_PRODUCT_LABEL_STORE_FK_PRODUCT_LABEL = 'spy_product_label_store.fk_product_label';

    /**
     * @return void
     */
    public function testWriteCollectionByProductLabelEventsRefreshesProductPageSearchWithCorrectData(): void
    {
        // Arrange
        $productPageSearchFacadeMock = $this->getProductPageSearchFacadeMock();

        $productTransfer1 = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productTransfer1->getFkProductAbstract()
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract()
        );

        $eventTransfers = [
            $this->getEventEntityTransfer([
                EventEntityTransfer::ID => $productLabelTransfer->getIdProductLabel(),
            ]),
        ];

        // Assert
        $productPageSearchFacadeMock->expects($this->once())
            ->method('refresh')
            ->with([
                $productTransfer1->getFkProductAbstract(),
                $productTransfer2->getFkProductAbstract(),
            ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductLabelEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductLabelEventsDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $productPageSearchFacadeMock = $this->getProductPageSearchFacadeMock();

        $productTransfer = $this->tester->haveProduct();
        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productTransfer->getFkProductAbstract()
        );

        $productLabelTransfer2 = $this->tester->haveProductLabel();

        $eventTransfers = [
            $this->getEventEntityTransfer([
                EventEntityTransfer::ID => $productLabelTransfer2->getIdProductLabel(),
            ]),
        ];

        // Assert
        $productPageSearchFacadeMock->expects($this->never())
            ->method('refresh');

        // Act
        $this->tester->getFacade()->writeCollectionByProductLabelEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductLabelProductAbstractEventsRefreshesProductPageSearchWithCorrectData(): void
    {
        // Arrange
        $productPageSearchFacadeMock = $this->getProductPageSearchFacadeMock();

        $productTransfer1 = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();

        $eventTransfers = [
            $this->getEventEntityTransfer([
                EventEntityTransfer::FOREIGN_KEYS => [
                    static::COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT => $productTransfer1->getFkProductAbstract(),
                ],
            ]),
            $this->getEventEntityTransfer([
                EventEntityTransfer::FOREIGN_KEYS => [
                    static::COL_PRODUCT_LABEL_PRODUCT_ABSTRACT_FK_PRODUCT_ABSTRACT => $productTransfer2->getFkProductAbstract(),
                ],
            ]),
        ];

        // Assert
        $productPageSearchFacadeMock->expects($this->once())
            ->method('refresh')
            ->with([
                $productTransfer1->getFkProductAbstract(),
                $productTransfer2->getFkProductAbstract(),
            ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductLabelProductAbstractEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductLabelProductAbstractEventsDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $productPageSearchFacadeMock = $this->getProductPageSearchFacadeMock();

        $productTransfer = $this->tester->haveProduct();

        $eventTransfers = [
            $this->getEventEntityTransfer([
                EventEntityTransfer::ID => $productTransfer->getFkProductAbstract(),
            ]),
        ];

        // Assert
        $productPageSearchFacadeMock->expects($this->never())
            ->method('refresh');

        // Act
        $this->tester->getFacade()->writeCollectionByProductLabelProductAbstractEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductLabelStoreEventsRefreshesProductPageSearchWithCorrectData(): void
    {
        // Arrange
        $productPageSearchFacadeMock = $this->getProductPageSearchFacadeMock();

        $productTransfer1 = $this->tester->haveProduct();
        $productLabelTransfer1 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productTransfer1->getFkProductAbstract()
        );

        $productTransfer2 = $this->tester->haveProduct();
        $productLabelTransfer2 = $this->tester->haveProductLabel();
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract()
        );

        $eventTransfers = [
            $this->getEventEntityTransfer([
                EventEntityTransfer::FOREIGN_KEYS => [
                    static::COL_PRODUCT_LABEL_STORE_FK_PRODUCT_LABEL => $productLabelTransfer1->getIdProductLabel(),
                ],
            ]),
            $this->getEventEntityTransfer([
                EventEntityTransfer::FOREIGN_KEYS => [
                    static::COL_PRODUCT_LABEL_STORE_FK_PRODUCT_LABEL => $productLabelTransfer2->getIdProductLabel(),
                ],
            ]),
        ];

        // Assert
        $productPageSearchFacadeMock->expects($this->once())
            ->method('refresh')
            ->with([
                $productTransfer1->getFkProductAbstract(),
                $productTransfer2->getFkProductAbstract(),
            ]);

        // Act
        $this->tester->getFacade()->writeCollectionByProductLabelStoreEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testWriteCollectionByProductLabelStoreEventsDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $productPageSearchFacadeMock = $this->getProductPageSearchFacadeMock();

        $productTransfer = $this->tester->haveProduct();

        $eventTransfers = [
            $this->getEventEntityTransfer([
                EventEntityTransfer::ID => $productTransfer->getFkProductAbstract(),
            ]),
        ];

        // Assert
        $productPageSearchFacadeMock->expects($this->never())
            ->method('refresh');

        // Act
        $this->tester->getFacade()->writeCollectionByProductLabelStoreEvents($eventTransfers);
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransferWithProductLabelIdsReturnsUpdatedTransferWithCorrectData(): void
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore();
        $storeRelationSeedData = [
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
        ];
        $productLabelTransfer1 = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $productLabelTransfer2 = $this->tester->haveProductLabel([
            ProductLabelTransfer::STORE_RELATION => $storeRelationSeedData,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer1->getIdProductLabel(),
            $productTransfer1->getFkProductAbstract()
        );
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer2->getIdProductLabel(),
            $productTransfer2->getFkProductAbstract()
        );

        $productLabelIdsMappedByIdProductAbstractAndStoreName = [
            $productTransfer1->getFkProductAbstract() => [
                $storeTransfer->getName() => [
                    $productLabelTransfer1->getIdProductLabel(),
                ],
            ],
            $productTransfer2->getFkProductAbstract() => [
                $storeTransfer->getName() => [
                    $productLabelTransfer2->getIdProductLabel(),
                ],
            ],
        ];

        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setProductAbstractIds([
                $productTransfer1->getFkProductAbstract(),
                $productTransfer2->getFkProductAbstract(),
            ])
            ->setPayloadTransfers([
                $this->getProductPayloadTransfer([
                    ProductPayloadTransfer::ID_PRODUCT_ABSTRACT => $productTransfer1->getFkProductAbstract(),
                ]),
                $this->getProductPayloadTransfer([
                    ProductPayloadTransfer::ID_PRODUCT_ABSTRACT => $productTransfer2->getFkProductAbstract(),
                ]),
            ]);

        // Act
        $expandedProductPageLoadTransfer = $this->tester->getFacade()
            ->expandProductPageDataTransferWithProductLabelIds($productPageLoadTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductPayloadTransfer $payloadTransfer */
        foreach ($expandedProductPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $this->assertNotEmpty($payloadTransfer->getLabelIds());
            $this->assertEquals(
                $productLabelIdsMappedByIdProductAbstractAndStoreName[$payloadTransfer->getIdProductAbstract()],
                $payloadTransfer->getLabelIds()
            );
        }
    }

    /**
     * @return void
     */
    public function testExpandProductPageDataTransferWithProductLabelIdsDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $productTransfer1 = $this->tester->haveProduct();
        $productTransfer2 = $this->tester->haveProduct();

        $productPageLoadTransfer = (new ProductPageLoadTransfer())
            ->setProductAbstractIds([
                $productTransfer1->getFkProductAbstract(),
                $productTransfer2->getFkProductAbstract(),
            ])
            ->setPayloadTransfers([
                $this->getProductPayloadTransfer([
                    ProductPayloadTransfer::ID_PRODUCT_ABSTRACT => $productTransfer1->getFkProductAbstract(),
                ]),
                $this->getProductPayloadTransfer([
                    ProductPayloadTransfer::ID_PRODUCT_ABSTRACT => $productTransfer2->getFkProductAbstract(),
                ]),
            ]);

        // Act
        $expandedProductPageLoadTransfer = $this->tester->getFacade()
            ->expandProductPageDataTransferWithProductLabelIds($productPageLoadTransfer);

        // Assert
        foreach ($expandedProductPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $this->assertEmpty($payloadTransfer->getLabelIds());
        }
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchInterface
     */
    protected function getProductPageSearchFacadeMock(): ProductLabelSearchToProductPageSearchInterface
    {
        $productPageSearchFacadeMock = $this
            ->getMockBuilder(ProductLabelSearchToProductPageSearchInterface::class)
            ->getMock();

        $this->tester->setDependency(
            ProductLabelSearchDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH,
            $productPageSearchFacadeMock
        );

        return $productPageSearchFacadeMock;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    protected function getEventEntityTransfer(array $seedData): EventEntityTransfer
    {
        return (new EventEntityBuilder())->seed($seedData)->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayloadTransfer(array $seedData): ProductPayloadTransfer
    {
        return (new ProductPayloadBuilder())->seed($seedData)->build();
    }
}
