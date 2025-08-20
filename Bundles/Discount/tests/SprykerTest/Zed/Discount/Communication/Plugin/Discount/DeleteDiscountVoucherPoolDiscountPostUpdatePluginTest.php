<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\Discount;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Discount\Communication\Plugin\Discount\DeleteDiscountVoucherPoolDiscountPostUpdatePlugin;
use SprykerTest\Zed\Discount\DiscountCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group Discount
 * @group DeleteDiscountVoucherPoolDiscountPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class DeleteDiscountVoucherPoolDiscountPostUpdatePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountCommunicationTester
     */
    protected DiscountCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPostUpdateDeletesDiscountVoucherPoolWhenDiscountTypeIsCartRuleAndDiscountVoucherPoolIdIsSet(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->getDiscountConfiguration();
        $idDiscountVoucherPool = $discountConfiguratorTransfer->getDiscountVoucherOrFail()->getFkDiscountVoucherPoolOrFail();
        $this->tester->unsetFkDiscountVoucherPool($idDiscountVoucherPool);

        // Act
        (new DeleteDiscountVoucherPoolDiscountPostUpdatePlugin())->postUpdate($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getDiscountVoucherEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
        $this->assertSame(0, $this->tester->getDiscountVoucherPoolEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
    }

    /**
     * @return void
     */
    public function testPostUpdateDoesNothingWhenDiscountTypeIsVoucher(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->getDiscountConfiguration(DiscountConstants::TYPE_VOUCHER);
        $idDiscountVoucherPool = $discountConfiguratorTransfer->getDiscountVoucherOrFail()->getFkDiscountVoucherPoolOrFail();
        $this->tester->unsetFkDiscountVoucherPool($idDiscountVoucherPool);

        // Act
        (new DeleteDiscountVoucherPoolDiscountPostUpdatePlugin())->postUpdate($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getDiscountVoucherEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
        $this->assertSame(1, $this->tester->getDiscountVoucherPoolEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
    }

    /**
     * @return void
     */
    public function testPostUpdateDoesNothingWhenDiscountVoucherIsNotSetToDiscountConfiguration(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->getDiscountConfiguration();
        $idDiscountVoucherPool = $discountConfiguratorTransfer->getDiscountVoucherOrFail()->getFkDiscountVoucherPoolOrFail();

        $discountConfiguratorTransfer->setDiscountVoucher(null);
        $this->tester->unsetFkDiscountVoucherPool($idDiscountVoucherPool);

        // Act
        (new DeleteDiscountVoucherPoolDiscountPostUpdatePlugin())->postUpdate($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getDiscountVoucherEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
        $this->assertSame(1, $this->tester->getDiscountVoucherPoolEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
    }

    /**
     * @return void
     */
    public function testPostUpdateDoesNothingWhenDiscountVoucherPoolIdIsNotSetToDiscountVoucher(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->getDiscountConfiguration();
        $idDiscountVoucherPool = $discountConfiguratorTransfer->getDiscountVoucherOrFail()->getFkDiscountVoucherPoolOrFail();
        $discountConfiguratorTransfer->getDiscountVoucherOrFail()->setFkDiscountVoucherPool(null);
        $this->tester->unsetFkDiscountVoucherPool($idDiscountVoucherPool);

        // Act
        (new DeleteDiscountVoucherPoolDiscountPostUpdatePlugin())->postUpdate($discountConfiguratorTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getDiscountVoucherEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
        $this->assertSame(1, $this->tester->getDiscountVoucherPoolEntitiesCountByIdDiscountVoucherPool(
            $idDiscountVoucherPool,
        ));
    }

    /**
     * @return void
     */
    public function testPostUpdateThrowsExceptionWhenDiscountGeneralIsNotSetToDiscountConfigurator(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->getDiscountConfiguration();
        $discountConfiguratorTransfer->setDiscountGeneral(null);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "discountGeneral" of transfer `Generated\Shared\Transfer\DiscountConfiguratorTransfer` is null.');

        // Act
        (new DeleteDiscountVoucherPoolDiscountPostUpdatePlugin())->postUpdate($discountConfiguratorTransfer);
    }

    /**
     * @return void
     */
    public function testPostUpdateThrowsExceptionWhenDiscountTypeIsNotSetToDiscountGeneral(): void
    {
        // Arrange
        $discountConfiguratorTransfer = $this->getDiscountConfiguration();
        $discountConfiguratorTransfer->getDiscountGeneralOrFail()->setDiscountType(null);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "discountType" of transfer `Generated\Shared\Transfer\DiscountGeneralTransfer` is null.');

        // Act
        (new DeleteDiscountVoucherPoolDiscountPostUpdatePlugin())->postUpdate($discountConfiguratorTransfer);
    }

    /**
     * @param string $discountType
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function getDiscountConfiguration(string $discountType = DiscountConstants::TYPE_CART_RULE): DiscountConfiguratorTransfer
    {
        $discountGeneralTransfer = $this->tester->haveDiscount([
            DiscountGeneralTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
        ]);
        $discountVoucherTransfer = $this->tester->haveGeneratedVoucherCodes([
            DiscountVoucherTransfer::ID_DISCOUNT => $discountGeneralTransfer->getIdDiscount(),
        ]);
        $discountGeneralTransfer->setDiscountType($discountType);

        return (new DiscountConfiguratorTransfer())
            ->setDiscountGeneral($discountGeneralTransfer)
            ->setDiscountVoucher($discountVoucherTransfer);
    }
}
