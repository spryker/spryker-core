<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Glue\ProductsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ConcreteProductsByPickingListItemsBackendResourceRelationshipPlugin;
use SprykerTest\Glue\ProductsBackendApi\ProductsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductsBackendApi
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group ConcreteProductsByPickingListItemsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ConcreteProductsByPickingListItemsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LIST_ITEMS = 'picking-list-items';

    /**
     * @uses \Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @var \SprykerTest\Glue\ProductsBackendApi\ProductsBackendApiTester
     */
    protected ProductsBackendApiTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testShouldAddPickingListItemsConcreteProductsRelationships(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $pickingListItemTransfer = $this->createPickingListItemTransfer($productConcreteTransfer);
        $pickingListTransfer = $this->createPickingListTransfer([
            PickingListTransfer::USER => null,
            PickingListTransfer::WAREHOUSE => $this->tester->haveStock(),
            PickingListTransfer::PICKING_LIST_ITEMS => [
                $pickingListItemTransfer,
            ],
        ]);

        $glueResourceTransfers = [
            $this->createGlueResourceTransfer($this->getPickingList($pickingListTransfer)),
        ];

        // Act
        (new ConcreteProductsByPickingListItemsBackendResourceRelationshipPlugin())->addRelationships(
            $glueResourceTransfers,
            new GlueRequestTransfer(),
        );

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $this->assertSame(static::RESOURCE_CONCRETE_PRODUCTS, $glueResourceTransfer->getType());
        $this->assertInstanceOf(ProductConcretesBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertSame($productConcreteTransfer->getSku(), $glueResourceTransfer->getId());
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function createPickingListTransfer(array $seed = []): PickingListTransfer
    {
        $pickingListTransfer = (new PickingListBuilder($seed))
            ->withWarehouse()
            ->withUser()
            ->build();

        if (array_key_exists(PickingListTransfer::PICKING_LIST_ITEMS, $seed)) {
            $pickingListTransfer = $pickingListTransfer->setPickingListItems(
                new ArrayObject($seed[PickingListTransfer::PICKING_LIST_ITEMS]),
            );
        }

        return $this->tester->havePickingList($pickingListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListTransfer
     */
    protected function getPickingList(PickingListTransfer $pickingListTransfer): PickingListTransfer
    {
        /** @var \Spryker\Zed\PickingList\Business\PickingListFacadeInterface $pickingListFacade */
        $pickingListFacade = $this->tester->getLocator()->pickingList()->facade();

        $pickingListConditionsTransfer = (new PickingListConditionsTransfer())->addUuid($pickingListTransfer->getUuid());
        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())
            ->setPickingListConditions($pickingListConditionsTransfer);

        return $pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer)->getPickingLists()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListItemTransfer
     */
    protected function createPickingListItemTransfer(
        ProductConcreteTransfer $productConcreteTransfer
    ): PickingListItemTransfer {
        $stockTransfer = $this->tester->haveStock();

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::SKU => $productConcreteTransfer->getSku(),
            ItemTransfer::WAREHOUSE => $stockTransfer->toArray(),
        ]))->build();

        $saveOrderTransfer = $this->tester->haveOrder(
            $itemTransfer->toArray(),
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return (new PickingListItemBuilder([
            PickingListItemTransfer::ORDER_ITEM => $saveOrderTransfer->getOrderItems()->getIterator()->current(),
        ]))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createGlueResourceTransfer(PickingListTransfer $pickingListTransfer): GlueResourceTransfer
    {
        $pickingListItemsBackendApiAttributesTransfer = (new PickingListItemsBackendApiAttributesTransfer())
            ->fromArray($pickingListTransfer->getPickingListItems()->getIterator()->current()->toArray(), true);

        return (new GlueResourceTransfer())
            ->setType(static::RESOURCE_PICKING_LIST_ITEMS)
            ->setAttributes($pickingListItemsBackendApiAttributesTransfer);
    }
}
