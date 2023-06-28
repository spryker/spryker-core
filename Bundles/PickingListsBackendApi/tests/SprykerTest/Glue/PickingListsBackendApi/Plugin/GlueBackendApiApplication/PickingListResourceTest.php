<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication;

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
 * @group GlueBackendApiApplication
 * @group PickingListResourceTest
 * Add your own group annotations below this line
 */
class PickingListResourceTest extends Unit
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
        $type = $this->tester->createPickingListsBackendResourcePlugin()->getType();

        //Assert
        $this->assertSame($type, PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS);
    }

    /**
     * @return void
     */
    public function testDeclaredMethodsShouldReturnCorrectGlueResourceMethodCollectionTransfer(): void
    {
        //Act
        $glueResourceMethodCollectionTransfer = $this->tester->createPickingListsBackendResourcePlugin()->getDeclaredMethods();

        //Assert
        $this->assertInstanceOf(GlueResourceMethodCollectionTransfer::class, $glueResourceMethodCollectionTransfer);
        $this->assertInstanceOf(GlueResourceMethodConfigurationTransfer::class, $glueResourceMethodCollectionTransfer->getGet());
        $this->assertInstanceOf(GlueResourceMethodConfigurationTransfer::class, $glueResourceMethodCollectionTransfer->getGetCollection());
    }
}
