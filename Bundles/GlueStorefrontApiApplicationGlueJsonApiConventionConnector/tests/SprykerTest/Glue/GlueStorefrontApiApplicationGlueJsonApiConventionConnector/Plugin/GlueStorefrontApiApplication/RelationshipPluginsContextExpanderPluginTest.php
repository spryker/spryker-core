<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Plugin\GlueStorefrontApiApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\GlueStorefrontApiApplicationGlueJsonApiConventionConnectorDependencyProvider;
use Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Plugin\GlueStorefrontApiApplication\RelationshipPluginsContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueStorefrontApiApplicationGlueJsonApiConventionConnector
 * @group Plugin
 * @group GlueStorefrontApiApplication
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
     * @var \SprykerTest\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\GlueStorefrontApiApplicationGlueJsonApiConventionConnectorTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testRunPluginExpendAndCheckResponseTransfer(): void
    {
        //Arrange
        $this->tester->setDependency(
            GlueStorefrontApiApplicationGlueJsonApiConventionConnectorDependencyProvider::PLUGINS_RESOURCE_RELATIONSHIP,
            $this->createResourceRelationshipCollection(),
        );

        $apiApplicationSchemaContextTransfer = $this->buildApiApplicationSchemaContextTransfer();
        $relationshipPluginsContextExpanderPlugin = new RelationshipPluginsContextExpanderPlugin();

        //Act
        $apiApplicationSchemaContextTransfer = $relationshipPluginsContextExpanderPlugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        /** @var \Generated\Shared\Transfer\RelationshipPluginsContextTransfer $relationshipPluginsContextTransfer */
        $relationshipPluginsContextTransfer = $apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()->offsetGet(0);

        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()->count());
        $this->assertEquals(static::TYPE, $relationshipPluginsContextTransfer->getResourceType());
        $this->assertEquals(static::RESOURCE_TYPE, $relationshipPluginsContextTransfer->getRelationship());
    }

    /**
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function buildApiApplicationSchemaContextTransfer(): ApiApplicationSchemaContextTransfer
    {
        $customRoutesContextTransfer = new ResourceContextTransfer();
        $customRoutesContextTransfer->setResourceType(static::TYPE);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $apiApplicationSchemaContextTransfer->addResourceContext($customRoutesContextTransfer);

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @return \Spryker\Glue\GlueStorefrontApiApplicationGlueJsonApiConventionConnector\Collection\ResourceRelationshipCollection
     */
    protected function createResourceRelationshipCollection(): ResourceRelationshipCollection
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
