<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\ServicePointsBackendApi\Plugin\GlueBackendApiApplication\ServicesBackendResourcePlugin;
use SprykerTest\Glue\ServicePointsBackendApi\ServicePointsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsBackendApi
 * @group Plugin
 * @group ServicesBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class ServicesBackendResourcePluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig::RESOURCE_SERVICES
     *
     * @var string
     */
    protected const RESOURCE_SERVICES = 'services';

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
        $servicesBackendResourcePlugin = new ServicesBackendResourcePlugin();

        // Act
        $type = $servicesBackendResourcePlugin->getType();

        // Assert
        $this->assertSame(static::RESOURCE_SERVICES, $type);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Arrange
        $servicesBackendResourcePlugin = new ServicesBackendResourcePlugin();

        // Act
        $glueResourceMethodCollectionTransfer = $servicesBackendResourcePlugin->getDeclaredMethods();

        // Assert
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGet());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGetCollection());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPatch());
    }
}
