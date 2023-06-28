<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PushNotificationsBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\PushNotificationsBackendApi\Plugin\GlueBackendApiApplication\PushNotificationSubscriptionsBackendResourcePlugin;
use SprykerTest\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PushNotificationsBackendApi
 * @group Plugin
 * @group PushNotificationSubscriptionsBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class PushNotificationSubscriptionsBackendResourcePluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS
     *
     * @var string
     */
    protected const RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS = 'push-notification-subscriptions';

    /**
     * @var \SprykerTest\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiTester
     */
    protected PushNotificationsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testGetTypeShouldReturnCorrectType(): void
    {
        // Arrange
        $pushNotificationSubscriptionsBackendResourcePlugin = new PushNotificationSubscriptionsBackendResourcePlugin();

        // Act
        $type = $pushNotificationSubscriptionsBackendResourcePlugin->getType();

        // Assert
        $this->assertSame(static::RESOURCE_PUSH_NOTIFICATION_SUBSCRIPTIONS, $type);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Arrange
        $pushNotificationSubscriptionsBackendResourcePlugin = new PushNotificationSubscriptionsBackendResourcePlugin();

        // Act
        $glueResourceMethodCollectionTransfer = $pushNotificationSubscriptionsBackendResourcePlugin->getDeclaredMethods();

        // Assert
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
    }
}
