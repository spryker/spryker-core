<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeUrlCriteriaTransfer;
use Spryker\Zed\Category\Persistence\CategoryRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Persistence
 * @group GetCategoryNodeUrlsTest
 * Add your own group annotations below this line
 */
class GetCategoryNodeUrlsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->categoryRepository = new CategoryRepository();
    }

    /**
     * @return void
     */
    public function testGetCategoryNodeUrlsFindsUrls(): void
    {
        // Arrange
        $localeTransfer = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();
        $this->tester->haveLocalizedCategory(['locale' => $localeTransfer]);

        // Act
        $urlTransfers = $this->categoryRepository->getCategoryNodeUrls(new CategoryNodeUrlCriteriaTransfer());

        // Assert
        $this->assertNotEmpty($urlTransfers);
    }

    /**
     * @return void
     */
    public function testGetCategoryNodeUrlsFindsUrlsByIdCategoryNodeList(): void
    {
        // Arrange
        $localeTransfer = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();

        $firstCategoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $localeTransfer]);
        $secondCategoryTransfer = $this->tester->haveLocalizedCategory(['locale' => $localeTransfer]);

        $categoryNodeUrlCriteriaTransfer = (new CategoryNodeUrlCriteriaTransfer())
            ->addIdCategoryNode($firstCategoryTransfer->getCategoryNode()->getIdCategoryNode())
            ->addIdCategoryNode($secondCategoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        $urlTransfers = $this->categoryRepository->getCategoryNodeUrls($categoryNodeUrlCriteriaTransfer);

        // Assert
        $this->assertCount(2, $urlTransfers);
        $this->assertSame($firstCategoryTransfer->getCategoryNode()->getIdCategoryNode(), $urlTransfers[0]->getFkResourceCategorynode());
        $this->assertSame($secondCategoryTransfer->getCategoryNode()->getIdCategoryNode(), $urlTransfers[1]->getFkResourceCategorynode());
    }
}
