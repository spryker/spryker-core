<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\BackendCustomRoutesContextExpanderPlugin;
use SprykerTest\Glue\GlueBackendApiApplication\Stub\TestRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Plugin
 * @group GlueBackendApiApplication
 * @group BackendCustomRoutesContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class BackendCustomRoutesContextExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueBackendApiApplication\GlueBackendApiApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandEmptyRouters(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER,
            [],
        );

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $backendCustomRoutesContextExpanderPlugin = new BackendCustomRoutesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $backendCustomRoutesContextExpanderPlugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEmpty($apiApplicationSchemaContextTransfer->getCustomRoutesContexts());
    }

    /**
     * @return void
     */
    public function testExpandWithRouters(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER,
            [
                new TestRouteProviderPlugin(),
            ],
        );
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $backendCustomRoutesContextExpanderPlugin = new BackendCustomRoutesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $backendCustomRoutesContextExpanderPlugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(2, $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->count());
        $this->assertEquals(['/get', '/post'], $this->mapCustomRoutesContextsToPathArray($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()));
    }

    /**
     * @param \ArrayObject $customRoutesContexts
     *
     * @return array<\Generated\Shared\Transfer\CustomRoutesContextTransfer>
     */
    protected function mapCustomRoutesContextsToPathArray(ArrayObject $customRoutesContexts): array
    {
        $result = [];

        foreach ($customRoutesContexts as $customRoutesContext) {
            $result[] = $customRoutesContext->getPath();
        }

        return $result;
    }
}
