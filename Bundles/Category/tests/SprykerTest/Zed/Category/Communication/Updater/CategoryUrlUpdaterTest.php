<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Communication\Updater;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Category\Business\Generator\UrlPathGenerator;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Communication\Updater\CategoryUrlUpdater;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Communication
 * @group Updater
 * @group CategoryUrlUpdaterTest
 * Add your own group annotations below this line
 */
class CategoryUrlUpdaterTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Category\CategoryCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Category\Communication\Updater\CategoryUrlUpdater
     */
    protected CategoryUrlUpdater $categoryUrlUpdater;

    /**
     * @var \SprykerTest\Zed\Category\CategoryConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected CategoryConfig|MockObject $categoryConfigMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->categoryConfigMock = $this->mockCategoryConfig();
        $this->categoryUrlUpdater = new CategoryUrlUpdater($this->categoryConfigMock);
    }

    /**
     * @return void
     */
    public function testUpdateCategoryUrlPathWithLanguagePrefix(): void
    {
        // Arrange
        $this->categoryConfigMock
            ->method('isFullLocaleNamesInUrlEnabled')
            ->willReturn(false);
        $localeTransfer = $this->tester->buildLocaleTransfer([
            'localeName' => 'en_US',
        ]);

        // Act
        $result = $this->categoryUrlUpdater->updateCategoryUrlPath([], $localeTransfer);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals([
            UrlPathGenerator::CATEGORY_NAME => 'en',
        ], $result[0]);
    }

    /**
     * @return void
     */
    public function testUpdateCategoryUrlPathWithLocalePrefix(): void
    {
        // Arrange
        $this->categoryConfigMock
            ->method('isFullLocaleNamesInUrlEnabled')
            ->willReturn(true);
        $localeTransfer = $this->tester->buildLocaleTransfer([
            'localeName' => 'en_US',
        ]);

        // Act
        $result = $this->categoryUrlUpdater->updateCategoryUrlPath([], $localeTransfer);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals([
            UrlPathGenerator::CATEGORY_NAME => 'en-us',
        ], $result[0]);
    }

    /**
     * @return \Spryker\Zed\Category\CategoryConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function mockCategoryConfig(): CategoryConfig|MockObject
    {
        return $this->createMock(CategoryConfig::class);
    }
}
