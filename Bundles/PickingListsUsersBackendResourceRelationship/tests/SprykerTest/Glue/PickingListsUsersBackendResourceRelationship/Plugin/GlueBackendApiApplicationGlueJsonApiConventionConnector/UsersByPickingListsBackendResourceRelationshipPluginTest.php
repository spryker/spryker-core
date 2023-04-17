<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsUsersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiPickingListsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListCollectionRequestTransfer;
use Generated\Shared\Transfer\UsersRestAttributesTransfer;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\UsersByPickingListsBackendResourceRelationshipPlugin;
use SprykerTest\Glue\PickingListsUsersBackendResourceRelationship\PickingListsUsersBackendResourceRelationshipTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsUsersBackendResourceRelationship
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group UsersByPickingListsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class UsersByPickingListsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS
     *
     * @var string
     */
    protected const RESOURCE_PICKING_LISTS = 'picking-lists';

    /**
     * @uses \Spryker\Glue\UsersBackendApi\UsersBackendApiConfig::RESOURCE_TYPE_USERS
     *
     * @var string
     */
    protected const RESOURCE_TYPE_USERS = 'users';

    /**
     * @var \SprykerTest\Glue\PickingListsUsersBackendResourceRelationship\PickingListsUsersBackendResourceRelationshipTester
     */
    protected PickingListsUsersBackendResourceRelationshipTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddUsersResourceRelationshipToGlueResourceTransfer(): void
    {
        $userTransfer = $this->tester->haveUser();
        $pickingListTransfer = $this->tester->createPickingList($userTransfer);

        $pickingListTransfer->setUser($userTransfer);
        $this->tester->getPickingListFacade()->updatePickingListCollection(
            (new PickingListCollectionRequestTransfer())
                ->addPickingList($pickingListTransfer)
                ->setIsTransactional(true),
        );

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId($pickingListTransfer->getUuidOrFail())
                ->setType(static::RESOURCE_PICKING_LISTS)
                ->setAttributes(
                    (new ApiPickingListsAttributesTransfer())->fromArray($pickingListTransfer->toArray(), true),
                ),
        ];

        // Act
        (new UsersByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());

        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        $this->assertCount(1, $glueRelationshipTransfer->getResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();
        $this->assertSame(static::RESOURCE_TYPE_USERS, $glueResourceTransfer->getType());
        $this->assertInstanceOf(UsersRestAttributesTransfer::class, $glueResourceTransfer->getAttributes());
        $this->assertSame($userTransfer->getUuidOrFail(), $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldNotAddUsersResourceRelationshipToGlueResourceWithoutUserAssignment(): void
    {
        $userTransfer = $this->tester->haveUser();
        $pickingListTransfer = $this->tester->createPickingList($userTransfer);

        $glueResourceTransfers = [
            (new GlueResourceTransfer())
                ->setId($pickingListTransfer->getUuidOrFail())
                ->setType(static::RESOURCE_PICKING_LISTS)
                ->setAttributes(
                    (new ApiPickingListsAttributesTransfer())->fromArray($pickingListTransfer->toArray(), true),
                ),
        ];

        // Act
        (new UsersByPickingListsBackendResourceRelationshipPlugin())->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfers[0]->getRelationships());
    }
}
