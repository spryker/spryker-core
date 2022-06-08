<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider;
use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\BackendResourcesContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplication
 * @group Plugin
 * @group GlueBackendApiApplication
 * @group BackendResourcesContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class BackendResourcesContextExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandEmptyResources(): void
    {
        $this->tester->setDependency(
            GlueBackendApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            [],
        );

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $plugin = new BackendResourcesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEmpty($apiApplicationSchemaContextTransfer->getCustomRoutesContexts());
    }

    /**
     * @return void
     */
    public function testExpandWithResources(): void
    {
        $this->tester->setDependency(
            GlueBackendApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            $this->createArrayOfResourcesBuilderPluginMock(),
        );

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $plugin = new BackendResourcesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(2, $apiApplicationSchemaContextTransfer->getResourceContexts()->count());

        $fooResourceContext = $apiApplicationSchemaContextTransfer->getResourceContexts()[0];
        $barResourceContext = $apiApplicationSchemaContextTransfer->getResourceContexts()[1];

        $this->assertEquals('Foo', $fooResourceContext->getResourceType());
        $this->assertEquals('FooResourceController', $fooResourceContext->getController());
        $this->assertEquals('Bar', $barResourceContext->getResourceType());
        $this->assertEquals('BarResourceController', $barResourceContext->getController());
    }

    /**
     * @return array
     */
    protected function createArrayOfResourcesBuilderPluginMock(): array
    {
        $declaredMethods = (new GlueResourceMethodCollectionTransfer())
            ->setGetCollection(new GlueResourceMethodConfigurationTransfer())
            ->setGet(new GlueResourceMethodConfigurationTransfer())
            ->setPost(
                (new GlueResourceMethodConfigurationTransfer())->setAction('postAction'),
            )
            ->setPatch(
                (new GlueResourceMethodConfigurationTransfer())->setAction('patchAction'),
            )
            ->setDelete((new GlueResourceMethodConfigurationTransfer())->setAction('deleteAction'));

        $result = [];

        foreach (['Foo', 'Bar'] as $name) {
            $testPluginMock = $this
                ->getMockBuilder(ResourceInterface::class)
                ->getMock();

            $testPluginMock->expects($this->once())
                ->method('getType')
                ->willReturn($name);
            $testPluginMock->expects($this->once())
                ->method('getController')
                ->willReturn($name . 'ResourceController');

            $testPluginMock->expects($this->once())
                ->method('getDeclaredMethods')
                ->willReturn($declaredMethods);

            $result[] = $testPluginMock;
        }

        return $result;
    }
}
