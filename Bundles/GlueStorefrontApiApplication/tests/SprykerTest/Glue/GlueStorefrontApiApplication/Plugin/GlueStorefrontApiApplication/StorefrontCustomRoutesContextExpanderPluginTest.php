<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication\StorefrontCustomRoutesContextExpanderPlugin;
use SprykerTest\Glue\GlueStorefrontApiApplication\Stub\TestRouteProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Plugin
 * @group GlueStorefrontApiApplication
 * @group StorefrontCustomRoutesContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class StorefrontCustomRoutesContextExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandEmptyRouters(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueStorefrontApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER,
            [],
        );

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $plugin = new StorefrontCustomRoutesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

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
            GlueStorefrontApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER,
            [
               new TestRouteProviderPlugin(),
            ],
        );

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $plugin = new StorefrontCustomRoutesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(2, count($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()));
        $this->assertEquals(['/get', '/post'], $this->mapCustomRoutesContextsToPathArray($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()));
    }

    /**
     * @param mixed $customRoutesContexts
     *
     * @return array<string>
     */
    protected function mapCustomRoutesContextsToPathArray($customRoutesContexts): array
    {
        $result = [];

        foreach ($customRoutesContexts as $customRoutesContext) {
            $result[] = $customRoutesContext->getPath();
        }

        return $result;
    }
}
