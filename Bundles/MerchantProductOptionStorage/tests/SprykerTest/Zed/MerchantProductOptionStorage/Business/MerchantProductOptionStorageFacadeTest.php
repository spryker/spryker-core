<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOptionStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOptionStorage
 * @group Business
 * @group Facade
 * @group MerchantProductOptionStorageFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOptionStorageFacadeTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var \SprykerTest\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFilterProductOptionsSuccess(): void
    {
        // Arrange
        $productOptionGroupTransfer = $this->tester->haveProductOptionGroup();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => static::STATUS_WAITING_FOR_APPROVAL,
        ]);

        // Act
        $productOptionTransfers = $this->tester->getFacade()->filterProductOptions([
            (new ProductOptionTransfer())->setIdGroup($productOptionGroupTransfer->getIdProductOptionGroup()),
            (new ProductOptionTransfer())->setIdGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup()),
        ]);

        // Assert
        $this->assertCount(1, $productOptionTransfers);
    }
}
