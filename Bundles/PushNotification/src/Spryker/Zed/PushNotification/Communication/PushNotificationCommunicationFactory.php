<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\PushNotification\PushNotificationConfig getConfig()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PushNotification\Business\PushNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface getRepository()
 */
class PushNotificationCommunicationFactory extends AbstractCommunicationFactory
{
}
