<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Model;

use Codeception\Test\Unit;
use Spryker\Zed\Search\Business\Model\SearchInstaller;
use SprykerTest\Zed\Search\SearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Model
 * @group SearchInstallerTest
 * Add your own group annotations below this line
 */
class SearchInstallerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected SearchBusinessTester $tester;

    /**
     * @return void
     */
    public function testInstallForInstallPluginInterface(): void
    {
        // Arrange
        $installPluginMock = $this->tester->createInstallPlugin();
        $loggerMock = $this->tester->createLogger();

        // Assert
        $installPluginMock->method('install')->with($loggerMock);

        // Arrange
        $searchInstaller = new SearchInstaller($loggerMock, [$installPluginMock]);

        // Act
        $searchInstaller->install(SearchBusinessTester::STORE);
    }

    /**
     * @return void
     */
    public function testInstallForStoreAwareInstallPluginInterface(): void
    {
        // Arrange
        $installPluginMock = $this->tester->createStoreAwareInstallPlugin();
        $loggerMock = $this->tester->createLogger();

        // Assert
        $installPluginMock->method('install')->with($loggerMock, SearchBusinessTester::STORE);

        // Arrange
        $searchInstaller = new SearchInstaller($loggerMock, [$installPluginMock]);

        // Act
        $searchInstaller->install(SearchBusinessTester::STORE);
    }

    /**
     * @return void
     */
    public function testInstallForSearchInstallerInterface(): void
    {
        // Arrange
        $installPluginMock = $this->tester->createSearchInstaller();

        // Assert
        $installPluginMock->method('install')->with();

        // Arrange
        $searchInstaller = new SearchInstaller($this->tester->createLogger(), [$installPluginMock]);

        // Act
        $searchInstaller->install();
    }
}
