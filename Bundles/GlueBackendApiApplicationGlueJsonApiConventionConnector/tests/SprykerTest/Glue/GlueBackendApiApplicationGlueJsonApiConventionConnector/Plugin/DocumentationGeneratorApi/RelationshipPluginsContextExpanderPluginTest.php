<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Plugin\DocumentationGeneratorApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\GlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider;
use Spryker\Glue\GlueBackendApiApplicationGlueJsonApiConventionConnector\Plugin\DocumentationGeneratorApi\RelationshipPluginsContextExpanderPlugin;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use SprykerTest\Zed\GlueBackendApiApplicationGlueJsonApiConventionConnector\GlueBackendApiApplicationGlueJsonApiConventionConnectorTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group Plugin
 * @group DocumentationGeneratorApi
 * @group RelationshipPluginsContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class RelationshipPluginsContextExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TYPE = 'TYPE';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE = 'RESOURCE_TYPE';

    /**
     * @var \SprykerTest\Zed\GlueBackendApiApplicationGlueJsonApiConventionConnector\GlueBackendApiApplicationGlueJsonApiConventionConnectorTester
     */
    protected GlueBackendApiApplicationGlueJsonApiConventionConnectorTester $tester;

    /**
     * @return void
     */
    public function testExpandDocumentationGenerationContextWithRelationshipInformation(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueBackendApiApplicationGlueJsonApiConventionConnectorDependencyProvider::PLUGINS_RESOURCE_RELATIONSHIP,
            $this->createResourceRelationshipCollection(),
        );

        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer();
        $relationshipPluginsContextExpanderPlugin = new RelationshipPluginsContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $relationshipPluginsContextExpanderPlugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $relationshipPluginsContextTransfer = $apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()->getIterator()->current();

        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()->count());
        $this->assertEquals(static::TYPE, $relationshipPluginsContextTransfer->getResourceType());
        $this->assertEquals(static::RESOURCE_TYPE, $relationshipPluginsContextTransfer->getRelationship());
    }

    /**
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function createApiApplicationSchemaContextTransfer(): ApiApplicationSchemaContextTransfer
    {
        $customRoutesContextTransfer = new ResourceContextTransfer();
        $customRoutesContextTransfer->setResourceType(static::TYPE);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $apiApplicationSchemaContextTransfer->addResourceContext($customRoutesContextTransfer);

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function createResourceRelationshipCollection(): ResourceRelationshipCollectionInterface
    {
        $resourceRelationshipCollection = new ResourceRelationshipCollection();

        $resourceRelationshipPlugin = $this
            ->getMockBuilder(ResourceRelationshipPluginInterface::class)
            ->getMock();
        $resourceRelationshipPlugin->expects($this->exactly(2))
            ->method('getRelationshipResourceType')
            ->willReturn(static::RESOURCE_TYPE);
        $resourceRelationshipCollection->addRelationship(static::TYPE, $resourceRelationshipPlugin);

        return $resourceRelationshipCollection;
    }
}
