<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ServicePointsBackendResourcePlugin;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Plugin
 * @group ServicePointsBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class ServicePointsBackendResourcePluginTest extends Unit
{
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
        $servicePointsBackendResourcePlugin = new ServicePointsBackendResourcePlugin();

        // Act
        $type = $servicePointsBackendResourcePlugin->getType();

        // Assert
        $this->assertSame(static::RESOURCE_SERVICE_POINTS, $type);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Arrange
        $servicePointsBackendResourcePlugin = new ServicePointsBackendResourcePlugin();

        // Act
        $glueResourceMethodCollectionTransfer = $servicePointsBackendResourcePlugin->getDeclaredMethods();

        // Assert
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGet());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGetCollection());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPatch());
    }
}
