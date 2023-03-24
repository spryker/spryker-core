<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsBackendApi
 * @group Plugin
 * @group PickingListItemResourceTest
 * Add your own group annotations below this line
 */
class PickingListItemResourceTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester
     */
    protected PickingListsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testGetTypeShouldReturnCorrectType(): void
    {
        //Act
        $type = $this->tester
            ->createPickingListItemsBackendResourcePlugin()
            ->getType();

        //Assert
        $this->assertSame($type, PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_ITEMS);
    }

    /**
     * @return void
     */
    public function testDeclaredMethodsShouldReturnCorrectGlueResourceMethodCollectionTransfer(): void
    {
        //Act
        $glueResourceMethodCollectionTransfer = $this->tester
            ->createPickingListItemsBackendResourcePlugin()
            ->getDeclaredMethods();

        //Assert
        $this->assertInstanceOf(GlueResourceMethodCollectionTransfer::class, $glueResourceMethodCollectionTransfer);

        $patchEndpoint = $glueResourceMethodCollectionTransfer->getPatch();
        $this->assertInstanceOf(GlueResourceMethodConfigurationTransfer::class, $patchEndpoint);
    }
}
