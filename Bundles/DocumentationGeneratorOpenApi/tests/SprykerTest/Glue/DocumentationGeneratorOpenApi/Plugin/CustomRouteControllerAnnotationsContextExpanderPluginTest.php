<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidCustomRouteException;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\CustomRouteControllerAnnotationsContextExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorOpenApi
 * @group Plugin
 * @group CustomRouteControllerAnnotationsContextExpanderPluginTest
 * Add your own group annotations below this line
 */
class CustomRouteControllerAnnotationsContextExpanderPluginTest extends Unit
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
            codecept_data_dir() . 'CustomRouteControllerAnnotationsContextExpanderPluginTest/Glue/%1$s/Controller/',
        ]);
    }

    /**
     * @return void
     */
    public function testCallExpandAndAssertTransferForGetAction(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer(
            'Spryker\Glue\CustomRouteControllerAnnotationsContextExpanderPluginTest\Controller\TestGetController',
            'getAction',
            'get',
        );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->count());
        $customRoutesContextTransfer = $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()[0];
        $this->assertSame(['Retrieves test by id.'], $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getSummary());
        $this->assertEquals('Generated\Shared\Transfer\TestsRestAttributesTransfer', $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getResponseAttributesClassName());
        $this->assertSame(['404' => 'Test not found.'], $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getResponses());
        $this->assertEquals(2, $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getParameters()->count());
        $this->assertEquals('acceptLanguage', $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getParameters()[0]->getRef());
        $this->assertEquals('q', $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getParameters()[1]->getName());
        $this->assertEquals('query', $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getParameters()[1]->getIn());
        $this->assertEquals('Description.', $customRoutesContextTransfer->getPathAnnotation()->getGetResourceById()->getParameters()[1]->getDescription());
    }

    /**
     * @return void
     */
    public function testCallExpandAndAssertTransferForPostAction(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer(
            'Spryker\Glue\CustomRouteControllerAnnotationsContextExpanderPluginTest\Controller\TestPostController',
            'postAction',
            'post',
        );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->count());
        $customRoutesContextTransfer = $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()[0];
        $this->assertSame(['Creates test resource.'], $customRoutesContextTransfer->getPathAnnotation()->getPost()->getSummary());
        $this->assertEquals('Generated\Shared\Transfer\TestsRestAttributesTransfer', $customRoutesContextTransfer->getPathAnnotation()->getPost()->getResponseAttributesClassName());
        $this->assertSame(['404' => 'Test not found.'], $customRoutesContextTransfer->getPathAnnotation()->getPost()->getResponses());
        $this->assertEquals(2, $customRoutesContextTransfer->getPathAnnotation()->getPost()->getParameters()->count());
        $this->assertEquals('acceptLanguage', $customRoutesContextTransfer->getPathAnnotation()->getPost()->getParameters()[0]->getRef());
        $this->assertEquals('q', $customRoutesContextTransfer->getPathAnnotation()->getPost()->getParameters()[1]->getName());
        $this->assertEquals('query', $customRoutesContextTransfer->getPathAnnotation()->getPost()->getParameters()[1]->getIn());
        $this->assertEquals('Description.', $customRoutesContextTransfer->getPathAnnotation()->getPost()->getParameters()[1]->getDescription());
    }

    /**
     * @return void
     */
    public function testExpandForPatchAction(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer(
            'Spryker\Glue\CustomRouteControllerAnnotationsContextExpanderPluginTest\Controller\TestPatchController',
            'patchAction',
            'patch',
        );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->count());
        $customRoutesContextTransfer = $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()[0];
        $this->assertSame(['Edits test resource by id.'], $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getSummary());
        $this->assertEquals('Generated\Shared\Transfer\TestsRestAttributesTransfer', $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getResponseAttributesClassName());
        $this->assertSame(['404' => 'Test not found.'], $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getResponses());
        $this->assertEquals(2, $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getParameters()->count());
        $this->assertEquals('acceptLanguage', $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getParameters()[0]->getRef());
        $this->assertEquals('q', $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getParameters()[1]->getName());
        $this->assertEquals('query', $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getParameters()[1]->getIn());
        $this->assertEquals('Description.', $customRoutesContextTransfer->getPathAnnotation()->getPatch()->getParameters()[1]->getDescription());
    }

    /**
     * @return void
     */
    public function testExpandForDeleteAction(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer(
            'Spryker\Glue\CustomRouteControllerAnnotationsContextExpanderPluginTest\Controller\TestDeleteController',
            'deleteAction',
            'delete',
        );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->count());
        $customRoutesContextTransfer = $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()[0];
        $this->assertSame(['Deletes tests resource.'], $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getSummary());
        $this->assertEquals('Generated\Shared\Transfer\TestsRestAttributesTransfer', $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getResponseAttributesClassName());
        $this->assertSame(['404' => 'Test not found.'], $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getResponses());
        $this->assertEquals(2, $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getParameters()->count());
        $this->assertEquals('acceptLanguage', $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getParameters()[0]->getRef());
        $this->assertEquals('q', $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getParameters()[1]->getName());
        $this->assertEquals('query', $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getParameters()[1]->getIn());
        $this->assertEquals('Description.', $customRoutesContextTransfer->getPathAnnotation()->getDelete()->getParameters()[1]->getDescription());
    }

    /**
     * @return void
     */
    public function testExpandNotSetControllerInvalidCustomRouteException(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = (new ApiApplicationSchemaContextTransfer())
            ->addCustomRoutesContext(
                (new CustomRoutesContextTransfer())
                    ->setDefaults([
                        '_resourceName' => 'test',
                        '_method' => 'delete',
                    ]),
            );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Assert
        $this->expectException(InvalidCustomRouteException::class);

        //Act
        $plugin->expand($apiApplicationSchemaContextTransfer);
    }

    /**
     * @return void
     */
    public function testExpandAndControllerNotFoundAction(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer(
            'Spryker\Glue\CustomRouteControllerAnnotationsContextExpanderPluginTest\Controller\TestNotFoundActionController',
            'getAction',
            'get',
        );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertEquals(1, $apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->count());
        $this->assertEmpty($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()[0]->getPathAnnotation());
    }

    /**
     * @return void
     */
    public function testExpandControllerActionWithoutAnnotation(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = $this->createApiApplicationSchemaContextTransfer(
            'Spryker\Glue\CustomRouteControllerAnnotationsContextExpanderPluginTest\Controller\MissedAnnotationController',
            'getCollectionAction',
            'get',
        );

        $plugin = new CustomRouteControllerAnnotationsContextExpanderPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $apiApplicationSchemaContextTransfer = $plugin->expand($apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertNull($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->offsetGet(0)->getPathAnnotation());
    }

    /**
     * @param string $controller
     * @param string $action
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function createApiApplicationSchemaContextTransfer(string $controller, string $action, string $method): ApiApplicationSchemaContextTransfer
    {
        return (new ApiApplicationSchemaContextTransfer())
            ->addCustomRoutesContext(
                (new CustomRoutesContextTransfer())
                    ->setDefaults([
                        '_resourceName' => 'test',
                        '_controller' => [$controller, $action],
                        '_method' => $method,
                    ]),
            );
    }
}
