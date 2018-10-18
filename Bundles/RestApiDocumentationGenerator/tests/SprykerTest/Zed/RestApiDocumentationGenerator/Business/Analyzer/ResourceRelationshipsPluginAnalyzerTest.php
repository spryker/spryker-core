<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Codeception\Test\Unit;
use Spryker\Glue\GlueApplication\Plugin\Rest\ResourceRelationshipCollectionProviderPlugin;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRelationshipPlugin;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRoutePlugin;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Plugin\TestResourceRouteRelatedPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
 * @group Business
 * @group Analyzer
 * @group ResourceRelationshipsPluginAnalyzerTest
 * Add your own group annotations below this line
 */
class ResourceRelationshipsPluginAnalyzerTest extends Unit
{
    protected const RELATIONSHIP_VALUE = 'test-resource-with-relationship';

    /**
     * @return void
     */
    public function testGetResourceRelationshipsWillReturnRelationshipNameForPluginWithRelationships(): void
    {
        $resourceRelationshipsPluginAnalyzer = $this->getResourceRelationshipsPluginAnalyzer();
        $plugin = new TestResourceRoutePlugin();

        $relationships = $resourceRelationshipsPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        $this->assertNotEmpty($relationships);
        $this->assertCount(1, $relationships);
        $this->assertEquals(static::RELATIONSHIP_VALUE, $relationships[0]);
    }

    /**
     * @return void
     */
    public function testGetResourceRelationshipsWillReturnEMptyArrayForPluginWithoutRelationships(): void
    {
        $resourceRelationshipsPluginAnalyzer = $this->getResourceRelationshipsPluginAnalyzer();
        $plugin = new TestResourceRouteRelatedPlugin();

        $relationships = $resourceRelationshipsPluginAnalyzer->getResourceRelationshipsForResourceRoutePlugin($plugin);

        $this->assertEmpty($relationships);
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourceRelationshipsPluginAnalyzerInterface
     */
    protected function getResourceRelationshipsPluginAnalyzer(): ResourceRelationshipsPluginAnalyzerInterface
    {
        return new ResourceRelationshipsPluginAnalyzer($this->getResourceRelationshipCollectionPlugins());
    }

    /**
     * @return \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected function getResourceRelationshipCollectionPlugins(): array
    {
        return [
            $this->getResourceRelationshipCollectionPlugin(),
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface
     */
    protected function getResourceRelationshipCollectionPlugin(): ResourceRelationshipCollectionProviderPluginInterface
    {
        $pluginMock = $this->getMockBuilder(ResourceRelationshipCollectionProviderPlugin::class)
            ->setMethods(['getResourceRelationshipCollection'])
            ->getMock();
        $pluginMock->method('getResourceRelationshipCollection')
            ->willReturn($this->getResourceRelationshipCollection());

        return $pluginMock;
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceRelationshipCollection(): ResourceRelationshipCollectionInterface
    {
        $resourceRelationshipCollection = new ResourceRelationshipCollection();
        $resourceRelationshipCollection->addRelationship(
            'test-resource',
            new TestResourceRelationshipPlugin()
        );

        return $resourceRelationshipCollection;
    }
}
