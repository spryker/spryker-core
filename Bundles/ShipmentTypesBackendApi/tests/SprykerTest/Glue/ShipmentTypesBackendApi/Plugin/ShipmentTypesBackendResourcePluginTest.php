<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypesBackendApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ShipmentTypesBackendApiAttributesTransfer;
use Spryker\Glue\ShipmentTypesBackendApi\Plugin\GlueBackendApiApplication\ShipmentTypesBackendResourcePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentTypesBackendApi
 * @group Plugin
 * @group ShipmentTypesBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class ShipmentTypesBackendResourcePluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig::RESOURCE_SHIPMENT_TYPES
     *
     * @var string
     */
    protected const RESOURCE_SHIPMENT_TYPES = 'shipment-types';

    /**
     * @return void
     */
    public function testGetTypeReturnsCorrectResourceType(): void
    {
        // Act
        $resourceType = (new ShipmentTypesBackendResourcePlugin())->getType();

        // Assert
        $this->assertSame(static::RESOURCE_SHIPMENT_TYPES, $resourceType);
    }

    /**
     * @return void
     */
    public function testGetDeclaredMethodsReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Act
        $glueResourceMethodCollectionTransfer = (new ShipmentTypesBackendResourcePlugin())->getDeclaredMethods();

        // Assert
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGet());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGetCollection());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPatch());
        $this->assertNull($glueResourceMethodCollectionTransfer->getDelete());

        $this->assertSame(ShipmentTypesBackendApiAttributesTransfer::class, $glueResourceMethodCollectionTransfer->getGetOrFail()->getAttributes());
        $this->assertSame(ShipmentTypesBackendApiAttributesTransfer::class, $glueResourceMethodCollectionTransfer->getGetCollectionOrFail()->getAttributes());
    }
}
