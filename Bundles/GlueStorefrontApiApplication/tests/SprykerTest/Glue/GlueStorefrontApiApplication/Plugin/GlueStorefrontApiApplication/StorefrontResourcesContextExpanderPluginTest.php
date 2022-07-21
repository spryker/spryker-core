<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider;
use Spryker\Glue\GlueStorefrontApiApplication\Plugin\DocumentationGeneratorApi\StorefrontResourcesContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplication
 * @group Plugin
 * @group GlueStorefrontApiApplication
 * @group StorefrontResourcesContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class StorefrontResourcesContextExpanderPluginTest extends Unit
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
        //Arrange
        $this->tester->setDependency(
            GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            [],
        );
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $plugin = new StorefrontResourcesContextExpanderPlugin();

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
        //Arrange
        $this->tester->setDependency(
            GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESOURCE,
            $this->createArrayOfResourcesBuilderPluginMock(),
        );

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $storefrontResourcesContextExpanderPlugin = new StorefrontResourcesContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $storefrontResourcesContextExpanderPlugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(2, $apiApplicationSchemaContextTransfer->getResourceContexts()->count());

        $first = $apiApplicationSchemaContextTransfer->getResourceContexts()[0];
        $second = $apiApplicationSchemaContextTransfer->getResourceContexts()[1];

        $this->assertEquals('Foo', $first->getResourceType());
        $this->assertEquals('FooResourceController', $first->getController());
        $this->assertEquals('Bar', $second->getResourceType());
        $this->assertEquals('BarResourceController', $second->getController());
        $this->assertInstanceOf(GlueResourceMethodCollectionTransfer::class, $first->getDeclaredMethods());
        $this->assertInstanceOf(GlueResourceMethodCollectionTransfer::class, $second->getDeclaredMethods());
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
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
