<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\TimestampMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group TimestampMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class TimestampMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsTimestampWhenItDoesNotExists(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $timestampMessageAttributeProviderPlugin = new TimestampMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $timestampMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNotNull($messageAttributesTransfer->getTimestamp());
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsValidTimestampWithAdequatePrecision(): void
    {
        // Arrange
        $messageAttributesTransfer1 = new MessageAttributesTransfer();
        $messageAttributesTransfer2 = new MessageAttributesTransfer();
        $timestampMessageAttributeProviderPlugin = new TimestampMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer1 = $timestampMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer1);
        $messageAttributesTransfer2 = $timestampMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer2);

        // Assert
        $this->assertGreaterThan(new DateTime($messageAttributesTransfer1->getTimestamp()), new DateTime($messageAttributesTransfer2->getTimestamp()));
    }
}
