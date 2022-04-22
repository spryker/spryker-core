<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\CorrelationIdMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group CorrelationIdMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class CorrelationIdMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const CORRELATION_ID = 'foo';

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsCorrelationIdWhenItDoesNotExists(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $correlationIdMessageAttributeProviderPlugin = new CorrelationIdMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $correlationIdMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNotNull($messageAttributesTransfer->getCorrelationId());
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesDoesNotAddCorrelationIdWhenItAlreadyExists(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setCorrelationId(static::CORRELATION_ID);
        $correlationIdMessageAttributeProviderPlugin = new CorrelationIdMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $correlationIdMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame(static::CORRELATION_ID, $messageAttributesTransfer->getCorrelationId());
    }
}
