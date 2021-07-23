<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business;

use Generated\Shared\Transfer\NavigationItemCollectionTransfer;
use Spryker\Zed\ZedNavigation\Communication\Plugin\BackofficeNavigationItemCollectionFilterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Facade
 * @group ZedNavigationFacadeTest
 * Add your own group annotations below this line
 */
class ZedNavigationFacadeTest extends ZedNavigationBusinessTester
{
    /**
     * @return void
     */
    public function testBuildNavigationShouldReturnArrayWithMenuAsKey(): void
    {
        $navigation = $this->getFacade()->buildNavigation('');

        $this->assertArrayHasKey('menu', $navigation);
    }

    /**
     * @return void
     */
    public function testFilterNavigationItemCollectionByRouteAccessibility(): void
    {
        // Arrange
        $navigationItemCollectionTransfer = $this->getNavigationWithoutFilterPlugins();
        $navigationItemCount = $navigationItemCollectionTransfer->getNavigationItems()->count();

        $this->provideNavigationItemCollectionFilterPlugins([new BackofficeNavigationItemCollectionFilterPlugin()]);
        $this->provideRouterFacade();

        $facade = $this->getFacadeWithCustomNavigationFile();

        // Act
        $navigationItemCollectionTransfer = $facade->filterNavigationItemCollectionByBackofficeRouteAccessibility($navigationItemCollectionTransfer);

        // Assert
        $this->assertEquals(2, $navigationItemCount);
        $this->assertEquals(1, $navigationItemCollectionTransfer->getNavigationItems()->count());
        $this->assertNotEquals($navigationItemCount, $navigationItemCollectionTransfer->getNavigationItems()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\NavigationItemCollectionTransfer
     */
    protected function getNavigationWithoutFilterPlugins(): NavigationItemCollectionTransfer
    {
        $zedNavigationConfigMock = $this->buildZedNavigationConfigMock();
        $zedNavigationBusinessFactoryMock = $this->buildZedNavigationBusinessFactoryMock($zedNavigationConfigMock);

        $this->provideNavigationItemCollectionFilterPlugins([]);

        $navigationCollector = $zedNavigationBusinessFactoryMock->createNavigationCollector();
        $defaultNavigationType = $zedNavigationConfigMock->getDefaultNavigationType();
        $navigationItemCollectionTransfer = new NavigationItemCollectionTransfer();

        $navigation = $navigationCollector->getNavigation($defaultNavigationType);

        return $this->mapNavigationItemsToNavigationItemCollectionTransfer($navigation, $navigationItemCollectionTransfer);
    }
}
