<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\RelationshipPluginsContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\RelationshipPluginAnnotationsContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorOpenApi
 * @group Plugin
 * @group RelationshipPluginAnnotationsContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class RelationshipPluginAnnotationsContextExpanderPluginTest extends Unit
{
 /**
  * @var \SprykerTest\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiCommunicationTester
  */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockConfigMethod('getAnnotationSourceDirectories', [
            codecept_data_dir() . 'RelationshipPluginAnnotationsContextExpanderPluginTest/Glue/%1$s/Plugin/',
        ]);
    }

    /**
     * @return void
     */
    public function testExpandTransferByResourceRelationshipPlugin(): void
    {
        //Arrange
        $classPath = '\Spryker\Glue\RelationshipPluginAnnotationsContextExpanderPluginTest\Plugin\TestItemResourceRelationshipPlugin';
        $relationshipPluginsContextTransfer = new RelationshipPluginsContextTransfer();
        $relationshipPluginsContextTransfer->setResourcePluginName($classPath);
        $relationshipPluginsContextTransfer->setResourceType('test');

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $apiApplicationSchemaContextTransfer->addRelationshipPluginsContext($relationshipPluginsContextTransfer);

        $plugin = new RelationshipPluginAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(
            '\Generated\Shared\Transfer\ItemRestAttributesTransfer',
            $apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()[0]->getRelationshipPluginAnnotationsContext()->getResourceAttributesClassName(),
        );
        $this->assertEquals(
            'test',
            $apiApplicationSchemaContextTransfer->getRelationshipPluginsContexts()[0]->getRelationshipPluginAnnotationsContext()->getResourceType(),
        );
    }
}
