<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategory\Communication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantCategoryResponseTransfer;
use Generated\Shared\Transfer\MerchantCategoryTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantCategory\Business\MerchantCategoryFacade;
use Spryker\Zed\MerchantCategory\Communication\Plugin\Merchant\MerchantCategoryMerchantExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCategory
 * @group Communication
 * @group MerchantCategoryMerchantExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantCategoryMerchantExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExpandExpandsMerchantDataWithCategories(): void
    {
        // Arrange
        $merchantCategoryMerchantExpanderPluginMock = $this->createMerchantCategoryMerchantExpanderPluginMock(
            (new MerchantCategoryResponseTransfer())
                ->addMerchantCategory(
                    (new MerchantCategoryTransfer())
                        ->setCategory(new CategoryTransfer())
                )
                ->setIsSuccessful(true)
        );

        // Act
        $merchantTransfer = $merchantCategoryMerchantExpanderPluginMock->expand(
            (new MerchantTransfer())->setIdMerchant(1)
        );

        // Assert
        $this->assertNotEmpty($merchantTransfer->getCategories());
    }

    /**
     * @return void
     */
    public function testExpandNotExpandMerchantDataWithCategoriesForNotSuccessfulResponse()
    {
        // Arrange
        $merchantCategoryMerchantExpanderPluginMock = $this->createMerchantCategoryMerchantExpanderPluginMock(
            (new MerchantCategoryResponseTransfer())
                ->addMerchantCategory(
                    (new MerchantCategoryTransfer())
                        ->setCategory(new CategoryTransfer())
                )
                ->setIsSuccessful(false)
        );

        // Act
        $merchantTransfer = $merchantCategoryMerchantExpanderPluginMock->expand(
            (new MerchantTransfer())->setIdMerchant(1)
        );

        // Assert
        $this->assertEmpty($merchantTransfer->getCategories());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryResponseTransfer $merchantCategoryResponseTransfer
     *
     * @return \Spryker\Zed\MerchantCategory\Communication\Plugin\Merchant\MerchantCategoryMerchantExpanderPlugin
     */
    protected function createMerchantCategoryMerchantExpanderPluginMock(
        MerchantCategoryResponseTransfer $merchantCategoryResponseTransfer
    ): MerchantCategoryMerchantExpanderPlugin {
        $merchantCategoryFacadeMock = $this->createMock(MerchantCategoryFacade::class);
        $merchantCategoryFacadeMock->method('get')
            ->willReturn($merchantCategoryResponseTransfer);

        $merchantCategoryMerchantExpanderPlugin = new MerchantCategoryMerchantExpanderPlugin();
        $merchantCategoryMerchantExpanderPlugin->setFacade($merchantCategoryFacadeMock);

        return $merchantCategoryMerchantExpanderPlugin;
    }
}
