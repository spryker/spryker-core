<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AuthorizationExtension\Dependency\Plugin;

/**
 * If implemented by the plugin together with {@link \Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface},
 * it terminates authorization flow execution if authorization for the current strategy is successful.
 */
interface DetachedAuthorizationStrategyPluginInterface
{

}
