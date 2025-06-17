<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Plugin\CartPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use SprykerFeature\Yves\SelfServicePortal\Plugin\CartPage\ServiceDateTimePreAddToCartPlugin;
use SprykerFeature\Yves\SelfServicePortal\Widget\SspShipmentTypeServicePointSelectorWidget;
use SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester;

/**
 * @group SprykerFeatureTest
 * @group Yves
 * @group SelfServicePortal
 * @group Plugin
 * @group CartPage
 * @group ServiceDateTimePreAddToCartPluginTest
 */
class ServiceDateTimePreAddToCartPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SCHEDULED_AT = '2023-12-25T10:00:00';

    /**
     * @var \SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester
     */
    protected SelfServicePortalYvesTester $tester;

    /**
     * @return void
     */
    public function testPreAddToCartExpandsItemWithScheduledAtWhenValidDateProvided(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();
        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT => static::TEST_SCHEDULED_AT,
        ];

        // Act
        $resultItemTransfer = (new ServiceDateTimePreAddToCartPlugin())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNotNull($resultItemTransfer->getMetadata());
        $this->assertSame(static::TEST_SCHEDULED_AT, $resultItemTransfer->getMetadataOrFail()->getScheduledAt());
    }

    /**
     * @return void
     */
    public function testPreAddToCartExpandsItemWithScheduledAtWhenItemAlreadyHasMetadata(): void
    {
        // Arrange
        $itemMetadataTransfer = new ItemMetadataTransfer();
        $itemTransfer = (new ItemTransfer())->setMetadata($itemMetadataTransfer);

        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT => static::TEST_SCHEDULED_AT,
        ];

        // Act
        $resultItemTransfer = (new ServiceDateTimePreAddToCartPlugin())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertSame($itemMetadataTransfer, $resultItemTransfer->getMetadata());
        $this->assertSame(static::TEST_SCHEDULED_AT, $resultItemTransfer->getMetadataOrFail()->getScheduledAt());
    }

    /**
     * @return void
     */
    public function testPreAddToCartDoesNotExpandItemWhenScheduledAtParamNotProvided(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();
        $params = [];

        // Act
        $resultItemTransfer = (new ServiceDateTimePreAddToCartPlugin())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getMetadata());
    }

    /**
     * @return void
     */
    public function testPreAddToCartDoesNotExpandItemWhenScheduledAtIsEmpty(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();
        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT => '',
        ];

        // Act
        $resultItemTransfer = (new ServiceDateTimePreAddToCartPlugin())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getMetadata());
    }

    /**
     * @return void
     */
    public function testPreAddToCartDoesNotExpandItemWhenScheduledAtIsNull(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();
        $params = [
            SspShipmentTypeServicePointSelectorWidget::DEFAULT_FORM_FIELD_ITEM_METADATA_SCHEDULED_AT => null,
        ];

        // Act
        $resultItemTransfer = (new ServiceDateTimePreAddToCartPlugin())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getMetadata());
    }

    /**
     * @return void
     */
    public function testPreAddToCartPreservesExistingMetadataWhenScheduledAtNotProvided(): void
    {
        // Arrange
        $itemMetadataTransfer = new ItemMetadataTransfer();
        $itemTransfer = (new ItemTransfer())->setMetadata($itemMetadataTransfer);
        $params = [];

        // Act
        $resultItemTransfer = (new ServiceDateTimePreAddToCartPlugin())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertSame($itemMetadataTransfer, $resultItemTransfer->getMetadata());
        $this->assertNull($resultItemTransfer->getMetadataOrFail()->getScheduledAt());
    }
}
