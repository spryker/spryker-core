<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Checkout\Plugin\Permission;

use Codeception\Test\Unit;
use Spryker\Shared\Checkout\Plugin\Permission\PlaceOrderWithAmountUpToPermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Checkout
 * @group Plugin
 * @group Permission
 * @group PlaceOrderWithAmountUpToPermissionPluginTest
 * Add your own group annotations below this line
 */
class PlaceOrderWithAmountUpToPermissionPluginTest extends Unit
{
    /**
     * @var string
     *
     * @uses \Spryker\Shared\Checkout\Plugin\Permission\PlaceOrderWithAmountUpToPermissionPlugin::FIELD_CENT_AMOUNT
     */
    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * @var int
     */
    protected const CENT_AMOUNT = 900;

    /**
     * @var int
     */
    protected const ZERO_CENT_AMOUNT = 0;

    /**
     * @return void
     */
    public function testCanWithCentAmountLessThanConfigurationReturnsTrue(): void
    {
        // Arrange
        $configuration = [static::FIELD_CENT_AMOUNT => static::CENT_AMOUNT];

        // Act
        $isAllowed = (new PlaceOrderWithAmountUpToPermissionPlugin())->can($configuration, static::CENT_AMOUNT - 1);

        // Assert
        $this->assertTrue($isAllowed);
    }

    /**
     * @return void
     */
    public function testCanWithCentAmountMoreThanConfigurationReturnsFalse(): void
    {
        // Arrange
        $configuration = [static::FIELD_CENT_AMOUNT => static::CENT_AMOUNT];

        // Act
        $isAllowed = (new PlaceOrderWithAmountUpToPermissionPlugin())->can($configuration, static::CENT_AMOUNT + 1);

        // Assert
        $this->assertFalse($isAllowed);
    }

    /**
     * @return void
     */
    public function testCanWithZeroCentAmountReturnsTrue(): void
    {
        // Arrange
        $configuration = [static::FIELD_CENT_AMOUNT => static::CENT_AMOUNT];

        // Act
        $isAllowed = (new PlaceOrderWithAmountUpToPermissionPlugin())->can($configuration, static::ZERO_CENT_AMOUNT);

        // Assert
        $this->assertTrue($isAllowed);
    }

    /**
     * @return void
     */
    public function testCanWithCentAmountNotProvidedReturnsFalse(): void
    {
        // Arrange
        $configuration = [static::FIELD_CENT_AMOUNT => static::CENT_AMOUNT];

        // Act
        $isAllowed = (new PlaceOrderWithAmountUpToPermissionPlugin())->can($configuration, null);

        // Assert
        $this->assertFalse($isAllowed);
    }

    /**
     * @return void
     */
    public function testCanWithConfigWithoutCentAmountSetReturnsTrue(): void
    {
        // Arrange
        $configuration = [];

        // Act
        $isAllowed = (new PlaceOrderWithAmountUpToPermissionPlugin())->can($configuration, static::CENT_AMOUNT);

        // Assert
        $this->assertTrue($isAllowed);
    }
}
