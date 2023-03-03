<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Communication\Plugin\Installer;

use Spryker\Zed\InstallerExtension\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Business\PushNotificationWebPushPhpFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 * @method \Spryker\Zed\PushNotificationWebPushPhp\Communication\PushNotificationWebPushPhpCommunicationFactory getFactory()
 */
class PushNotificationWebPushPhpProviderInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets push notification provider with {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} name by {@link \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface::getPushNotificationProviderCollection()}.
     * - Skips if provider already exists.
     * - Creates push notification provider with {@link \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig::WEB_PUSH_PHP_PROVIDER_NAME} name by {@link \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface::createPushNotificationProviderCollection()}.
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFacade()->installWebPushPhpProvider();
    }
}
