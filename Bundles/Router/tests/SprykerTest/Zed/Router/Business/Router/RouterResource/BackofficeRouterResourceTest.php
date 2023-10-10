<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Business\Router\RouterResource;

use Codeception\Test\Unit;
use Spryker\Zed\Router\Business\Router\RouterResource\BackofficeRouterResource;
use Spryker\Zed\Router\Business\RouterResource\ResourceInterface;
use Spryker\Zed\Router\RouterConfig;
use SprykerTest\Zed\Router\RouterBusinessTester;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Business
 * @group Router
 * @group RouterResource
 * @group BackofficeRouterResourceTest
 * Add your own group annotations below this line
 */
class BackofficeRouterResourceTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Router\RouterBusinessTester
     */
    protected RouterBusinessTester $tester;

    /**
     * @dataProvider getGetFinderDataProvider
     *
     * @param list<string> $notAllowedBackofficeControllerDirectories
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetFinder(array $notAllowedBackofficeControllerDirectories, int $expectedCount): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getControllerDirectories', [codecept_data_dir('Fixtures/Controller/')]);

        /** @var \Spryker\Zed\Router\RouterConfig $routerConfig */
        $routerConfig = $this->tester->mockConfigMethod('getNotAllowedBackofficeControllerDirectories', $notAllowedBackofficeControllerDirectories);

        // Act
        $finder = $this->createBackofficeRouterResource($routerConfig)->getFinder();

        // Assert
        $this->assertCount($expectedCount, $finder);
    }

    /**
     * @return array<array<list<string>|int>>
     */
    protected function getGetFinderDataProvider(): array
    {
        return [
            'Should not filter out files when all directories are allowed.' => [[], 1],
            'Should not filter out files when a directory does not exist.' => [['Mock'], 1],
            'Should filter out files when a directory does not exist.' => [['Fixtures'], 0],
        ];
    }

    /**
     * @param \Spryker\Zed\Router\RouterConfig $config
     *
     * @return \Spryker\Zed\Router\Business\RouterResource\ResourceInterface
     */
    protected function createBackofficeRouterResource(RouterConfig $config): ResourceInterface
    {
        return new class ($config) extends BackofficeRouterResource
        {
            /**
             * @return \Symfony\Component\Finder\Finder
             */
            public function getFinder(): Finder
            {
                return parent::getFinder();
            }
        };
    }
}
