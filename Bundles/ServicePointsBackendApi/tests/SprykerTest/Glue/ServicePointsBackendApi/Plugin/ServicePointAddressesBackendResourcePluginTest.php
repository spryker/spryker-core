<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServicePointAddressesBackendResourcePlugin;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Plugin
 * @group ServicePointAddressesBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class ServicePointAddressesBackendResourcePluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINT_ADDRESSES
     *
     * @var string
     */
    protected const RESOURCE_SERVICE_POINT_ADDRESSES = 'service-point-addresses';

    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS
     *
     * @var string
     */
    protected const RESOURCE_SERVICE_POINTS = 'service-points';

    /**
     * @var \SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester
     */
    protected ServicePointsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testGetTypeShouldReturnCorrectType(): void
    {
        // Arrange
        $servicePointAddressesBackendResourcePlugin = new ServicePointAddressesBackendResourcePlugin();

        // Act
        $type = $servicePointAddressesBackendResourcePlugin->getType();

        // Assert
        $this->assertSame(static::RESOURCE_SERVICE_POINT_ADDRESSES, $type);
    }

    /**
     * @return void
     */
    public function testGetParentResourceTypeShouldReturnCorrectType(): void
    {
        // Arrange
        $servicePointAddressesBackendResourcePlugin = new ServicePointAddressesBackendResourcePlugin();

        // Act
        $parentResourceType = $servicePointAddressesBackendResourcePlugin->getParentResourceType();

        // Assert
        $this->assertSame(static::RESOURCE_SERVICE_POINTS, $parentResourceType);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Arrange
        $servicePointAddressesBackendResourcePlugin = new ServicePointAddressesBackendResourcePlugin();

        // Act
        $glueResourceMethodCollectionTransfer = $servicePointAddressesBackendResourcePlugin->getDeclaredMethods();

        // Assert
        $this->assertNull($glueResourceMethodCollectionTransfer->getGet());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGetCollection());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPatch());
    }
}
