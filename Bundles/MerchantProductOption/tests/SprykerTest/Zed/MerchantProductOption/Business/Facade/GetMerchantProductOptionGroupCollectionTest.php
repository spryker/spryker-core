<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantProductOption\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductOptionGroupConditionsTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use SprykerTest\Zed\MerchantProductOption\MerchantProductOptionBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group MerchantProductOption
 * @group Business
 * @group Facade
 * @group GetMerchantProductOptionGroupCollectionTest
 * Add your own group annotations below this line
 */
class GetMerchantProductOptionGroupCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_PRODUCT_OPTION_GROUP = -1;

    /**
     * @var \SprykerTest\Zed\MerchantProductOption\MerchantProductOptionBusinessTester
     */
    protected MerchantProductOptionBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetMerchantProductOptionGroupCollectionReturnsEmptyCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->haveMerchantProductOptionGroup();

        $merchantProductOptionGroupConditionsTransfer = (new MerchantProductOptionGroupConditionsTransfer())
            ->addIdProductOptionGroup(static::ID_PRODUCT_OPTION_GROUP);
        $merchantProductOptionGroupCriteriaTransfer = (new MerchantProductOptionGroupCriteriaTransfer())
            ->setMerchantProductOptionGroupConditions($merchantProductOptionGroupConditionsTransfer);

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester->getFacade()
            ->getMerchantProductOptionGroupCollection($merchantProductOptionGroupCriteriaTransfer);

        // Assert
        $this->assertCount(0, $merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups());
    }

    /**
     * @return void
     */
    public function testGetMerchantProductOptionGroupCollectionReturnsCollectionWithOneMerchantProductOptionGroupWhileProductOptionGroupIdCriteriaMatched(): void
    {
        // Arrange
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup();
        $this->tester->haveMerchantProductOptionGroup();

        $merchantProductOptionGroupConditionsTransfer = (new MerchantProductOptionGroupConditionsTransfer())
            ->addIdProductOptionGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());
        $merchantProductOptionGroupCriteriaTransfer = (new MerchantProductOptionGroupCriteriaTransfer())
            ->setMerchantProductOptionGroupConditions($merchantProductOptionGroupConditionsTransfer);

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester->getFacade()
            ->getMerchantProductOptionGroupCollection($merchantProductOptionGroupCriteriaTransfer);

        // Assert
        $this->assertCount(1, $merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups());
        $this->assertSame(
            $merchantProductOptionGroupTransfer->getIdMerchantProductOptionGroup(),
            $merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups()->getIterator()->current()->getIdMerchantProductOptionGroup(),
        );
    }

    /**
     * @return void
     */
    public function testGetMerchantProductOptionGroupCollectionReturnsCollectionWithFiveMerchantProductOptionGroupsWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 15; $i++) {
            $this->tester->haveMerchantProductOptionGroup();
        }
        $merchantProductOptionGroupCriteriaTransfer = (new MerchantProductOptionGroupCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(5)->setOffset(10),
            );

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester->getFacade()
            ->getMerchantProductOptionGroupCollection($merchantProductOptionGroupCriteriaTransfer);

        // Assert
        $this->assertCount(5, $merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups());
    }
}
