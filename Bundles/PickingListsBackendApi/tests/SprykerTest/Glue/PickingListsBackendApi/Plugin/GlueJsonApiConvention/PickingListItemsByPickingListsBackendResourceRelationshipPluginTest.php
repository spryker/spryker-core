<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\PickingListItemsByPickingListsBackendResourceRelationshipPlugin;
use SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsBackendApi
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group PickingListItemsByPickingListsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class PickingListItemsByPickingListsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\PickingList\PickingListConfig::STATUS_READY_FOR_PICKING
     *
     * @var string
     */
    protected const STATUS_READY_FOR_PICKING = 'ready-for-picking';

    /**
     * @var string
     */
    protected const TEST_ITEM_UUID = 'test-item-uuid';

    /**
     * @var \SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester
     */
    protected PickingListsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddPickingListItemsRelationshipToGlueResourceTransfer(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([ItemTransfer::UUID => static::TEST_ITEM_UUID]))->build();
        $pickingListItemTransfer = (new PickingListItemBuilder([
            PickingListItemTransfer::ORDER_ITEM => $itemTransfer->toArray(),
        ]))->build();

        $stockTransfer = $this->tester->haveStock();

        $pickingListTransfer = (new PickingListBuilder([
            PickingListTransfer::WAREHOUSE => $stockTransfer->toArray(),
            PickingListTransfer::STATUS => static::STATUS_READY_FOR_PICKING,
        ]))->build()->addPickingListItem($pickingListItemTransfer);

        $pickingListTransfer = $this->tester->havePickingList($pickingListTransfer);

        $glueRequestTransfer = (new GlueRequestTransfer())->setAttributes([
            PickingListTransfer::UUID => $pickingListTransfer->getUuidOrFail(),
        ]);

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId($pickingListTransfer->getUuidOrFail())
                ->setType(PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS)
                ->setAttributes(new PickingListsBackendApiAttributesTransfer()),
        ];

        // Act
        (new PickingListItemsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(
            PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS,
            $glueResourceTransfer->getType(),
        );
        $this->assertInstanceOf(PickingListItemsBackendApiAttributesTransfer::class, $glueResourceTransfer->getAttributes());

        $this->assertSame($pickingListItemTransfer->getUuidOrFail(), $glueResourceTransfer->getId());
    }
}
