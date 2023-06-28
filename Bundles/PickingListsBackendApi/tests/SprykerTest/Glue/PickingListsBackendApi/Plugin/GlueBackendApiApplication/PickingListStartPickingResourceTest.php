<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PickingListsBackendApi\Plugin\GlueBackendApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\PickingListsBackendApi\Controller\PickingListStartPickingResourceController;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PickingListsBackendApi
 * @group Plugin
 * @group GlueBackendApiApplication
 * @group PickingListStartPickingResourceTest
 * Add your own group annotations below this line
 */
class PickingListStartPickingResourceTest extends Unit
{
    /**
     * @uses \Spryker\Zed\OauthWarehouse\Communication\Plugin\Authorization\WarehouseTokenAuthorizationStrategyPlugin::STRATEGY_NAME
     *
     * @var string
     */
    protected const STRATEGY_NAME = 'WarehouseTokenAuthorizationStrategy';

    /**
     * @var \SprykerTest\Glue\PickingListsBackendApi\PickingListsBackendApiTester
     */
    protected PickingListsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testGetTypeShouldReturnCorrectType(): void
    {
        //Act
        $type = $this->tester->createPickingListStartPickingBackendResourcePlugin()->getType();

        //Assert
        $this->assertSame(PickingListsBackendApiConfig::RESOURCE_PICKING_LIST_START_PICKING, $type);
    }

    /**
     * @return void
     */
    public function testGetParentResourceTypeShouldReturnCorrectParentResourceType(): void
    {
        // Act
        $parentResourceType = $this->tester->createPickingListStartPickingBackendResourcePlugin()->getParentResourceType();

        // Assert
        $this->assertSame(PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS, $parentResourceType);
    }

    /**
     * @return void
     */
    public function testGetControllerShouldReturnCorrectController(): void
    {
        // Act
        $controller = $this->tester->createPickingListStartPickingBackendResourcePlugin()->getController();

        // Assert
        $this->assertSame(PickingListStartPickingResourceController::class, $controller);
    }

    /**
     * @return void
     */
    public function testDeclaredMethodsShouldReturnCorrectGlueResourceMethodCollectionTransfer(): void
    {
        //Act
        $glueResourceMethodCollectionTransfer = $this->tester->createPickingListStartPickingBackendResourcePlugin()->getDeclaredMethods();

        //Assert
        $this->assertSame('postAction', $glueResourceMethodCollectionTransfer->getPostOrFail()->getActionOrFail());
    }

    /**
     * @return void
     */
    public function testGetRouteAuthorizationConfigurations(): void
    {
        // Act
        $routeAuthorizationConfigurations = $this->tester->createPickingListStartPickingBackendResourcePlugin()->getRouteAuthorizationConfigurations();

        // Assert
        $this->assertArrayHasKey(Request::METHOD_POST, $routeAuthorizationConfigurations);

        /** @var \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer $routeAuthorizationConfigTransfer */
        $routeAuthorizationConfigTransfer = $routeAuthorizationConfigurations[Request::METHOD_POST];

        $this->assertSame(static::STRATEGY_NAME, $routeAuthorizationConfigTransfer->getStrategies()[0]);
        $this->assertSame(Response::HTTP_FORBIDDEN, $routeAuthorizationConfigTransfer->getHttpStatusCodeOrFail());
        $this->assertSame(PickingListsBackendApiConfig::RESPONSE_CODE_AUTHORIZATION_FAILED, $routeAuthorizationConfigTransfer->getApiCodeOrFail());
        $this->assertSame(PickingListsBackendApiConfig::RESPONSE_DETAILS_AUTHORIZATION_FAILED, $routeAuthorizationConfigTransfer->getApiMessageOrFail());
    }
}
