<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategory\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCategory
 * @group Business
 * @group Facade
 * @group MerchantCategoryFacadeTest
 * Add your own group annotations below this line
 */
class MerchantCategoryFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantCategory\MerchantCategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function testGetReturnsListOfMerchantCategoriesByMerchantId(): void
    {
        // Arrange
        $merchantCategoryTransfer = $this->tester->haveMerchantCategory();

        // Act
        $merchantCategoryResponseTransfer = $this->tester->getFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantCategoryTransfer->getFkMerchant()),
            );

        // Assert
        $this->assertCount(1, $merchantCategoryResponseTransfer->getMerchantCategories());
    }

    /**
     * @return void
     */
    public function testGetReturnsNothingForNotExistingCategory(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        // Act
        $merchantCategoryResponseTransfer = $this->tester->getFacade()
            ->get(
                (new MerchantCategoryCriteriaTransfer())
                    ->setIdMerchant($merchantTransfer->getIdMerchant()),
            );

        // Assert
        $this->assertEmpty($merchantCategoryResponseTransfer->getMerchantCategories());
    }

    /**
     * @return void
     */
    public function testGetReturnsNothingForNoMerchantId(): void
    {
        // Arrange
        $this->tester->haveMerchantCategory();

        // Act
        $merchantCategoryResponseTransfer = $this->tester->getFacade()
            ->get(new MerchantCategoryCriteriaTransfer());

        // Assert
        $this->assertCount(1, $merchantCategoryResponseTransfer->getMerchantCategories());
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithCategoriesReturnsMerchantCollectionWithRelatedCategoriesIfExist(): void
    {
        // Arrange
        $merchantCollectionTransfer = new MerchantCollectionTransfer();
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchantWithCategories(2),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchant(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchantWithCategories(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchant(),
        );

        $this->tester->haveMerchant();

        // Act
        $resultMerchantCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantCollectionWithCategories($merchantCollectionTransfer);

        // Assert
        $this->assertCount(4, $resultMerchantCollectionTransfer->getMerchants());
        $this->tester->assertMerchantHasCategoriesCount(
            2,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(0),
        );
        $this->tester->assertMerchantHasCategoriesCount(
            0,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(1),
        );
        $this->tester->assertMerchantHasCategoriesCount(
            1,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(2),
        );
        $this->tester->assertMerchantHasCategoriesCount(
            0,
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(3),
        );
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithCategoriesReturnsEmptyMerchantCollectionIfEmptyMerchantCollectionWasPassed(): void
    {
        // Arrange
        $merchantCollectionTransfer = new MerchantCollectionTransfer();

        // Act
        $resultMerchantCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantCollectionWithCategories($merchantCollectionTransfer);

        // Assert
        $this->assertCount(0, $resultMerchantCollectionTransfer->getMerchants());
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithCategoriesThrowsExceptionIfMerchantCollectionMerchantHasNoIdMerchant(): void
    {
        // Arrange
        $merchantCollectionTransfer = (new MerchantCollectionTransfer())
            ->addMerchants(new MerchantTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()
            ->expandMerchantCollectionWithCategories($merchantCollectionTransfer);
    }
}
