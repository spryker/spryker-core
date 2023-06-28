<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\PushNotificationsBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\PushNotificationsBackendApi\Plugin\GlueBackendApiApplication\PushNotificationProvidersBackendResourcePlugin;
use SprykerTest\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group PushNotificationsBackendApi
 * @group Plugin
 * @group PushNotificationProvidersBackendResourcePluginTest
 * Add your own group annotations below this line
 */
class PushNotificationProvidersBackendResourcePluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig::RESOURCE_PUSH_NOTIFICATION_PROVIDERS
     *
     * @var string
     */
    protected const RESOURCE_PUSH_NOTIFICATION_PROVIDERS = 'push-notification-providers';

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
        $pushNotificationProvidersBackendResourcePlugin = new PushNotificationProvidersBackendResourcePlugin();

        // Act
        $type = $pushNotificationProvidersBackendResourcePlugin->getType();

        // Assert
        $this->assertSame(static::RESOURCE_PUSH_NOTIFICATION_PROVIDERS, $type);
    }

    /**
     * @return void
     */
    public function testReturnsCorrectGlueResourceMethodCollection(): void
    {
        // Arrange
        $pushNotificationProvidersBackendResourcePlugin = new PushNotificationProvidersBackendResourcePlugin();

        // Act
        $glueResourceMethodCollectionTransfer = $pushNotificationProvidersBackendResourcePlugin->getDeclaredMethods();

        // Assert
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGetCollection());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getGet());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPost());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getPatch());
        $this->assertNotNull($glueResourceMethodCollectionTransfer->getDelete());
    }
}
