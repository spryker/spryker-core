<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\DashboardDataExpanderPluginInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group GetDashboard
 *
 * Add your own group annotations below this line
 */
class GetDashboard extends Unit
{
 /**
  * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
  */
    protected SelfServicePortalBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetDashboardWillExecuteProviders(): void
    {
        // Arrange
        $mockDashboardDataExpanderPlugin = $this->createMock(DashboardDataExpanderPluginInterface::class);
        $mockDashboardDataExpanderPlugin->method('provideDashboardData')->willReturn((new DashboardResponseTransfer()));

        // Assert
        $mockDashboardDataExpanderPlugin->expects($this->once())->method('provideDashboardData');
        $this->tester->setDependency(
            SelfServicePortalDependencyProvider::PLUGINS_DASHBOARD_DATA_PROVIDER,
            [$mockDashboardDataExpanderPlugin],
        );

        // Act
        $this->tester->getFacade()->getDashboard(new DashboardRequestTransfer());
    }
}
