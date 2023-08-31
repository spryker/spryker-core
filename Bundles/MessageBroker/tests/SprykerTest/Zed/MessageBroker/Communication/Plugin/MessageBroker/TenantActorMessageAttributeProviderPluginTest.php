<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker\TenantActorMessageAttributeProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group MessageBroker
 * @group TenantActorMessageAttributeProviderPluginTest
 * Add your own group annotations below this line
 */
class TenantActorMessageAttributeProviderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMessageAttributesProvidedCorrectlyWhenTenantIdentifierIsPresentInConfig(): void
    {
        // Arrange
        $tenantIdentifier = 'dev-DE';
        $this->tester->setConfig(MessageBrokerConstants::TENANT_IDENTIFIER, $tenantIdentifier);
        $tenantActorMessageAttributeProviderPlugin = new TenantActorMessageAttributeProviderPlugin();

        // Act
        $messageAttributesTransfer = $tenantActorMessageAttributeProviderPlugin->provideMessageAttributes(new MessageAttributesTransfer());

        // Assert
        $this->assertSame($tenantIdentifier, $messageAttributesTransfer->getTenantIdentifier());
        $this->assertSame($tenantIdentifier, $messageAttributesTransfer->getActorId());
    }
}
