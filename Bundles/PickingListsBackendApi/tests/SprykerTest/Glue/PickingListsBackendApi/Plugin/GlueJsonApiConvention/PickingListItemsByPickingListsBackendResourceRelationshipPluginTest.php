<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
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
     * @var \SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester
     */
    protected PickingListsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddPickingListItemsRelationshipToGlueResourceTransfer(): void
    {
        // Arrange
        $pickingListTransfer = $this->tester->havePickingListWithPickingListItem();
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
        $this->assertPickingListItemResourceIncluded(
            $pickingListTransfer->getPickingListItems()->getIterator()->current(),
            $glueResourceTransfers,
        );
    }

    /**
     * @return void
     */
    public function testAddRelationshipShouldAddRelatedPickingListItemsWhenCollectionIsPaginated(): void
    {
        $this->tester->havePickingListWithPickingListItem();
        $this->tester->havePickingListWithPickingListItem();
        $pickingListTransfer = $this->tester->havePickingListWithPickingListItem();

        $paginationTransfer = (new PaginationTransfer())
            ->setLimit(1)
            ->setOffset(1);
        $glueRequestTransfer = (new GlueRequestTransfer())->setPagination($paginationTransfer);

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId($pickingListTransfer->getUuidOrFail())
                ->setType(PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS)
                ->setAttributes(new PickingListsBackendApiAttributesTransfer()),
        ];

        // Act
        (new PickingListItemsByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, $glueRequestTransfer);

        // Assert
        $this->assertPickingListItemResourceIncluded(
            $pickingListTransfer->getPickingListItems()->getIterator()->current(),
            $glueResourceTransfers,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return void
     */
    protected function assertPickingListItemResourceIncluded(
        PickingListItemTransfer $pickingListItemTransfer,
        array $glueResourceTransfers
    ): void {
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
