<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\MerchantProduct\Business\MerchantProductFacade;
use Spryker\Zed\MerchantProductApproval\Dependency\Facade\MerchantProductApprovalToMerchantProductFacadeBridge;
use Spryker\Zed\MerchantProductApproval\MerchantProductApprovalDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductApproval
 * @group Business
 * @group Facade
 * @group MerchantProductApprovalFacadeTest
 *
 * Add your own group annotations below this line
 */
class MerchantProductApprovalFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductApproval\MerchantProductApprovalBusinessTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @return void
     */
    public function testExpandProductAbstractExpandsWithDefaultProductAbstractApprovalStatus(): void
    {
        // Arrange
        $this->mockMerchantProductFacade(static::STATUS_WAITING_FOR_APPROVAL);
        $productAbstractTransfer = (new ProductAbstractTransfer())->setIdMerchant(1);

        // Act
        $productAbstractTransfer = $this->tester->getFacade()->expandProductAbstract($productAbstractTransfer);

        // Assert
        $this->assertSame(static::STATUS_WAITING_FOR_APPROVAL, $productAbstractTransfer->getApprovalStatus());
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractDoesNotExpandWhenDefaultProductAbstractApprovalStatusIsNotDefinedInDb(): void
    {
        // Arrange
        $this->mockMerchantProductFacade(null);
        $productAbstractTransfer = (new ProductAbstractTransfer())->setIdMerchant(1);

        // Act
        $productAbstractTransfer = $this->tester->getFacade()->expandProductAbstract($productAbstractTransfer);

        // Assert
        $this->assertEmpty($productAbstractTransfer->getApprovalStatus());
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractDoesNotExpandWhenApprovalStatusIsSet(): void
    {
        // Arrange
        $this->mockMerchantProductFacade(static::STATUS_WAITING_FOR_APPROVAL);
        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdMerchant(1)
            ->setApprovalStatus(static::STATUS_APPROVED);

        // Act
        $productAbstractTransfer = $this->tester->getFacade()->expandProductAbstract($productAbstractTransfer);

        // Assert
        $this->assertSame(static::STATUS_APPROVED, $productAbstractTransfer->getApprovalStatus());
    }

    /**
     * @return void
     */
    public function testExpandProductAbstractDoesNotExpandNotMerchantRelatedProduct(): void
    {
        // Arrange
        $productAbstractTransfer = (new ProductAbstractTransfer())->setIdMerchant(1);

        // Act
        $productAbstractTransfer = $this->tester->getFacade()->expandProductAbstract($productAbstractTransfer);

        // Assert
        $this->assertEmpty($productAbstractTransfer->getApprovalStatus());
    }

    /**
     * @param string|null $defaultProductAbstractApprovalStatus
     *
     * @return void
     */
    protected function mockMerchantProductFacade(?string $defaultProductAbstractApprovalStatus): void
    {
        $merchantProductFacadeMock = $this->getMockBuilder(MerchantProductFacade::class)
            ->getMock();
        $merchantProductFacadeMock->method('findMerchant')->willReturn(
            (new MerchantTransfer())->setDefaultProductAbstractApprovalStatus($defaultProductAbstractApprovalStatus),
        );
        $this->tester->setDependency(
            MerchantProductApprovalDependencyProvider::FACADE_MERCHANT_PRODUCT,
            new MerchantProductApprovalToMerchantProductFacadeBridge($merchantProductFacadeMock),
        );
    }
}
