<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServiceTypesBackendResourcePlugin;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Plugin
 * @group ServiceTypesBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class ServiceTypesBackendResourcePluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESOURCE_SERVICE_TYPES
     *
     * @var string
     */
    protected const RESOURCE_SERVICE_TYPES = 'service-types';

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
        $serviceTypesBackendResourcePlugin = new ServiceTypesBackendResourcePlugin();

        // Act
        $type = $serviceTypesBackendResourcePlugin->getType();

        // Assert
        $this->assertSame(static::RESOURCE_SERVICE_TYPES, $type);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Arrange
        $serviceTypesBackendResourcePlugin = new ServiceTypesBackendResourcePlugin();

        // Act
        $glueResourceMethodCollectionTransfer = $serviceTypesBackendResourcePlugin->getDeclaredMethods();

        // Assert
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGet());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGetCollection());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPatch());
    }
}
