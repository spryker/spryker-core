<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\AppIdentifierMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group AppIdentifierMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class AppIdentifierMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const APP_IDENTIFIER = 'foo';

    /**
     * @return void
     */
    public function testProvideMessageAttributesAddsAppIdentifierWhenItExists(): void
    {
        // Arrange
        putenv('AOP_APP_IDENTIFIER=foo');

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $appIdentifierMessageAttributeProviderPlugin = new AppIdentifierMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $appIdentifierMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertSame(static::APP_IDENTIFIER, $messageAttributesTransfer->getEmitter());
        putenv('AOP_APP_IDENTIFIER');
    }

    /**
     * @return void
     */
    public function testProvideMessageAttributesDoesNotAddAppIdentifierWhenItDoesNotExists(): void
    {
        // Arrange
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $appIdentifierMessageAttributeProviderPlugin = new AppIdentifierMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $appIdentifierMessageAttributeProviderPlugin->provideMessageAttributes($messageAttributesTransfer);

        // Assert
        $this->assertNull($messageAttributesTransfer->getEmitter());
    }
}
