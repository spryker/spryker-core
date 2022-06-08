<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AnnotationTransfer;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Generated\Shared\Transfer\RestAttributesTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\ControllerAnnotationsContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorOpenApi
 * @group Plugin
 * @group ControllerAnnotationsContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class ControllerAnnotationsContextExpanderPluginTest extends Unit
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
            codecept_data_dir() . 'ControllerAnnotationsContextExpanderPluginTest/Glue/%1$s/Controller/',
        ]);
    }

    /**
     * @return void
     */
    public function testCallExpandAndAssertTransferData(): void
    {
        //Arrange
        $resourceContextTransfer = new ResourceContextTransfer();
        $classNameSpace = '\Spryker\Glue\ControllerAnnotationsContextExpanderPluginTest\Controller\TestResourceController';
        $resourceContextTransfer->setController($classNameSpace);

        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $apiApplicationSchemaContextTransfer->addResourceContext($resourceContextTransfer);

        $plugin = new ControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getResourceContexts()->count());
        $resourceContext = $apiApplicationSchemaContextTransfer->getResourceContexts()[0];

        $this->assertInstanceOf(AnnotationTransfer::class, $resourceContext->getPathAnnotation()->getGetCollection());
        $this->assertEquals(RestAttributesTransfer::class, $resourceContext->getPathAnnotation()->getGetCollection()->getResponseAttributesClassName());
        $this->assertSame(['Retrieves collection of tests.'], $resourceContext->getPathAnnotation()->getGetCollection()->getSummary());

        $this->assertInstanceOf(AnnotationTransfer::class, $resourceContext->getPathAnnotation()->getGetResourceById());
        $this->assertEquals(RestAttributesTransfer::class, $resourceContext->getPathAnnotation()->getGetResourceById()->getResponseAttributesClassName());
        $this->assertSame(['Retrieves store by id.'], $resourceContext->getPathAnnotation()->getGetResourceById()->getSummary());
        $this->assertSame(['404' => 'Test not found.'], $resourceContext->getPathAnnotation()->getGetResourceById()->getResponses());
    }
}
