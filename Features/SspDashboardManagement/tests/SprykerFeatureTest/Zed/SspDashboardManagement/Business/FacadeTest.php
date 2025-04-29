<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspDashboardManagement\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use SprykerFeature\Zed\SspDashboardManagement\Dependency\Plugin\DashboardDataProviderPluginInterface;
use SprykerFeature\Zed\SspDashboardManagement\SspDashboardManagementDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspDashboardManagement
 * @group Business
 * @group FacadeTest
 *
 * Add your own group annotations below this line
 */
class FacadeTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspDashboardManagement\SspDashboardManagementBusinessTester
     */
    protected $tester;

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
        $mockDashboardDataProviderPlugin = $this->createMock(DashboardDataProviderPluginInterface::class);
        $mockDashboardDataProviderPlugin->method('provideDashboardData')->willReturn((new DashboardResponseTransfer()));

        // Assert
        $mockDashboardDataProviderPlugin->expects($this->once())->method('provideDashboardData');
        $this->tester->setDependency(
            SspDashboardManagementDependencyProvider::PLUGINS_DASHBOARD_DATA_PROVIDER,
            [$mockDashboardDataProviderPlugin],
        );

        // Act
        $this->tester->getFacade()->getDashboard(new DashboardRequestTransfer());
    }
}
