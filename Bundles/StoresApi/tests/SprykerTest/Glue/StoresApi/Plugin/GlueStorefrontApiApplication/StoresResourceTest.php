<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\StoresApi\Plugin\GlueStorefrontApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\StoresApi\StoresApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group StoresApi
 * @group Plugin
 * @group GlueStorefrontApiApplication
 * @group StoresResourceTest
 * Add your own group annotations below this line
 */
class StoresResourceTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\StoresApi\StoresApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetTypeShouldReturnCorrectType(): void
    {
        //Act
        $type = $this->tester->createStoresResource()
            ->getType();

        //Assert
        $this->assertSame($type, StoresApiConfig::RESOURCE_STORES);
    }

    /**
     * @return void
     */
    public function testDeclaredMethodsShouldReturnCorrectGlueResourceMethodCollectionTransfer(): void
    {
        //Act
        $glueResourceMethodCollectionTransfer = $this->tester->createStoresResource()
            ->getDeclaredMethods();

        //Assert
        $this->assertInstanceOf(GlueResourceMethodCollectionTransfer::class, $glueResourceMethodCollectionTransfer);

        $this->assertInstanceOf(
            GlueResourceMethodConfigurationTransfer::class,
            $glueResourceMethodCollectionTransfer->getGet(),
        );
        $this->assertInstanceOf(
            GlueResourceMethodConfigurationTransfer::class,
            $glueResourceMethodCollectionTransfer->getGetCollection(),
        );
    }
}
